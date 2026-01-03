<?php

namespace App\Http\Controllers;

use App\Exports\ParametersDataExport;
use App\Http\Requests\DeviceRequest;
use App\Models\Component;
use App\Models\Device;
use App\Models\DeviceParameters;
use App\Models\DeviceParametersValues;
use App\Models\DevicesComponents;
use App\Models\DeviceSettingPerDevice;
use App\Models\DeviceType;
use App\Models\LimitValues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::all();
        return view('admin.device.index', compact('devices'));
    }

    public function add()
    {
        $devices = Device::all();
        return view('admin.device.add', compact('devices'));
    }

    public function get_devices()
    {
        $user = auth()->user();
        $devices = Device::where('user_id', $user->id)->get();
        return view('admin.device.get_devices', compact('devices'));
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_device($id)
    {
        $device = Device::findOrFail($id);
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->save();

        $this->forgetSidebarCache($user?->id);

        return redirect()->route('admin.devices.add')->with('success', 'Device added successfully');
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_device($id)
    {
        $device = Device::findOrFail($id);
        $previousOwner = $device->user_id;
        $device->user_id = 0;
        $device->save();

        $this->forgetSidebarCache($previousOwner);

        return redirect()->route('admin.devices.get_devices')->with('success', 'Device added successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $types = DeviceType::all();
        return view('admin.device.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeviceRequest $request)
    {
        $device = new Device();
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $device->type_id = $request->type;
        $device->tolerance = $request->tolerance;
        $device->time_between_two_read = $request->time_between_two_read;
        if ($device->save()) {
            $this->forgetSidebarCache($user?->id);
            if (auth()->user()->role == 'Administrator') {
                return redirect()->route('admin.devices')->with('success', 'Data added successfully');
            } else {
                return redirect()->route('admin.devices.get_devices')->with('success', 'Data added successfully');
            }
        } else {

            return redirect()->route('admin.devices.create')->with('error', 'Data failed to add');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $device = Device::with([
            'deviceType.deviceParameters' => function ($query) {
                $query->orderBy('order');
            },
            'limitValues',
            'deviceComponents.component',
        ])->findOrFail($id);

        $componentContext = $this->buildComponentContext($device, $device->deviceComponents);

        $deviceComponents = $componentContext['device_components'];

        if ($deviceComponents->isEmpty()) {
            $components = Component::all();
            return view('admin.device_components.init', compact('components', 'device'));
        }

        $lineSettings = $componentContext['line_settings'];
        $tableSettings = $componentContext['table_settings'];

        $lineParameters = $componentContext['line_parameters'];
        $tableParameters = $componentContext['table_parameters'];

        $chartSampleLimit = (int) ($lineSettings['max_points'] ?? 720);
        if ($chartSampleLimit <= 0) {
            $chartSampleLimit = 720;
        }
        $chartSampleLimit = min($chartSampleLimit, 1440);

        $parameterTableLimit = (int) ($componentContext['number_of_row'] ?? 0);
        if ($parameterTableLimit <= 0) {
            $parameterTableLimit = 500;
        }
        $parameterTableLimit = min($parameterTableLimit, 1000);

        $baseLimit = max($parameterTableLimit, $chartSampleLimit * 2);
        $baseLimit = min($baseLimit, 5000);
        $allRecentReadings = $this->fetchDeviceReadings($device, null, null, $baseLimit);

        $chartWindowStart = Carbon::now()->copy()->startOfDay()->subHours(2);

        $recentParameters = $allRecentReadings
            ->filter(fn (DeviceParametersValues $reading) => $reading->read_at && $reading->read_at->greaterThanOrEqualTo($chartWindowStart))
            ->take($chartSampleLimit)
            ->values();

        $latestReading = $allRecentReadings->last() ?? $this->fetchLatestReading($device);

        $now = Carbon::now();
        $status = 'Offline';
        if ($latestReading && $latestReading->read_at) {
            $readAt = $latestReading->read_at;
            $threshold = (int) ($device->time_between_two_read ?? 0) + (int) ($device->tolerance ?? 0);

            if ($threshold > 0 && $readAt->diffInMinutes($now) <= $threshold) {
                $status = 'Online';
            }
        }

        $chartSeries = $this->buildSeries($recentParameters, $lineParameters);

        $latestValues = $recentParameters->last()->decoded_parameters ?? [];
        if (empty($latestValues) && $latestReading) {
            $latestValues = $latestReading->decoded_parameters ?? [];
        }

        [$color, $dangerColor, $warningCount] = $this->computeParameterStatus(
            $lineParameters,
            $latestValues,
            $device->limitValues,
            $componentContext['device_type_parameters']
        );

        $xValues = $recentParameters
            ->map(fn (DeviceParametersValues $reading) => $reading->read_at?->toIso8601String())
            ->filter()
            ->values()
            ->all();
        $xValues = array_values(array_unique($xValues));

        $parameterTableData = $allRecentReadings
            ->sortByDesc('read_at')
            ->take($parameterTableLimit)
            ->values();

        return view('admin.device.custom_show', [
            'multiColor' => [],
            'numberOfRow' => $parameterTableLimit,
            'device_type' => $componentContext['device_type'],
            'firstParameter' => $componentContext['first_parameter'],
            'color' => $color,
            'parameterTableColumn' => $tableParameters,
            'testParaColumn' => $componentContext['column_parameters'],
            'testPara' => $lineParameters,
            'device' => $device,
            'deviceComponents' => $deviceComponents,
            'dangerColor' => $dangerColor,
            'warning' => $warningCount,
            'status' => $status,
            'label' => 1,
            'xValues' => $xValues,
            'yValues' => [],
            'paraValues' => $chartSeries,
            'parameterTableData' => $parameterTableData,
            'latestValues' => $latestValues,
            'latestReadAt' => $latestReading?->read_at,
        ]);
    }

    private function fetchLatestReading(Device $device): ?DeviceParametersValues
    {
        $reading = DeviceParametersValues::query()
            ->select(['id', 'parameters', 'time_of_read'])
            ->where('device_id', $device->id)
            ->orderByDesc('id')
            ->first();

        if (!$reading) {
            return null;
        }

        $reading->decoded_parameters = $this->normalizeParameters($reading->parameters);
        $reading->read_at = $reading->time_of_read instanceof Carbon
            ? $reading->time_of_read->copy()
            : ($reading->time_of_read ? Carbon::parse($reading->time_of_read) : null);

        return $reading;
    }



    public function showParameterData($device_id,$parameter_id)
    {
//        $devFactory = DeviceFactory::findOrFail($devFactory_id);
        $device = Device::with(['deviceType.deviceParameters' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($device_id);

        $requestedParameter = DeviceParameters::with('parameterRangeColors')->findOrFail($parameter_id);
        $context = $this->buildComponentContext($device, $device->deviceComponents);

        $readings = $this->fetchDeviceReadings($device, Carbon::now()->startOfDay()->subHours(2), null, 720);

        $series = [];
        $xValues = [];
        $multiColor = [];

        if ($readings->isNotEmpty()) {
            $seriesValues = [];
            foreach ($readings as $reading) {
                $xValues[] = $reading->read_at?->toIso8601String();

                $value = $reading->decoded_parameters[$requestedParameter->code] ?? null;
                $seriesValues[] = $value ?? 0;

                $multiColor[] = $value !== null
                    ? $this->resolveRangeColor($requestedParameter, $value)
                    : '#00989d';
            }

            $series[] = $seriesValues;
        }

        $color = optional($context['device_type']->deviceParameters()->where('code', $requestedParameter->code)->first())->pivot->color ?? '#00989d';

        return [$series, array_values(array_unique($xValues)), $device, $multiColor, $requestedParameter, $color];
    }

    public function showParameterDataWithDate($device_id, $parameter_id, $from, $to)
    {
//        $devFactory = DeviceFactory::findOrFail($devFactory_id);
        $device = Device::with(['deviceType.deviceParameters' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($device_id);

        $requestedParameter = DeviceParameters::with('parameterRangeColors')->findOrFail($parameter_id);
        $context = $this->buildComponentContext($device, $device->deviceComponents);

        $range = $this->resolveDateRange((int) $from, (int) $to);

        $readings = $this->fetchDeviceReadings($device, $range['start'], $range['end'], 1000);

        $series = [];
        $xValues = [];
        $multiColor = [];

        if ($readings->isNotEmpty()) {
            $seriesValues = [];

            foreach ($readings as $reading) {
                $xValues[] = $reading->read_at?->toIso8601String();

                $value = $reading->decoded_parameters[$requestedParameter->code] ?? null;
                $seriesValues[] = $value ?? 0;

                $multiColor[] = $value !== null
                    ? $this->resolveRangeColor($requestedParameter, $value)
                    : '#00989d';
            }

            $series[] = $seriesValues;
        }

        $color = optional($context['device_type']->deviceParameters()->where('code', $requestedParameter->code)->first())->pivot->color ?? '#00989d';

        return [$series, array_values(array_unique($xValues)), $device, $multiColor, $requestedParameter, $color];
    }


    public function showWithDate($id, $from, $to)
    {
        $device = Device::with(['deviceType.deviceParameters' => function ($query) {
            $query->orderBy('order');
        }, 'limitValues'])->findOrFail($id);

        $context = $this->buildComponentContext($device, $device->deviceComponents);
        $lineParameters = $context['line_parameters'];

        $range = $this->resolveDateRange((int) $from, (int) $to);
        $limit = max(720, (int) ($context['line_settings']['max_points'] ?? 720));

        $readings = $this->fetchDeviceReadings($device, $range['start'], $range['end'], $limit);

        $series = $this->buildSeries($readings, $lineParameters);

        $xValues = $readings->map(fn (DeviceParametersValues $reading) => $reading->read_at?->toIso8601String())->filter()->values()->all();

        $lastReading = $readings->last();
        $latestValues = $lastReading?->decoded_parameters ?? [];
        [, , $warningCount] = $this->computeParameterStatus(
            $lineParameters,
            $latestValues,
            $device->limitValues,
            $context['device_type_parameters']
        );

        $status = $device->isOnline();
        $label = $this->resolveLabel((int) $from, (int) $to);

        return [$series, array_values(array_unique($xValues)), $device, $warningCount, $status ? 'Online' : 'Offline', $label];
    }

    public function getDeviceStatus($id)
    {
        $device = Device::findOrFail($id);

        $now = Carbon::now();
        $parameters = $device->deviceParameters;


        $lastPara = DeviceParametersValues::where('device_id', $id)->orderBy('id', 'desc')->first();
        if (count($parameters) > 0) {
            if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->m == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->d == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i <= ($device->time_between_two_read + $device->tolerance)) {
            $status = "Online";
        } else {
            $status = "Offline";
        }
        }
        return $status;
    }


    public function getColumnChartData($id)
    {
        $device = Device::with([
            'deviceType.deviceParameters' => function ($query) {
                $query->orderBy('order');
            },
            'deviceComponents',
        ])->findOrFail($id);

        $context = $this->buildComponentContext($device, $device->deviceComponents);
        $columnParameters = $context['column_parameters'];

        if ($columnParameters->isEmpty()) {
            $columnParameters = $context['device_type_parameters'];
        }

        $readings = $this->fetchDeviceReadings($device, Carbon::now()->subDay()->startOfDay(), null, 1440);

        $averages = $columnParameters->map(function ($parameter) use ($readings) {
            $code = $parameter->code ?? $parameter->name;

            $values = $readings
                ->map(fn (DeviceParametersValues $reading) => $reading->decoded_parameters[$code] ?? null)
                ->filter(fn ($value) => is_numeric($value));

            if ($values->isEmpty()) {
                return 0;
            }

            return round($values->avg(), 2);
        })->values()->all();

        return [[
            'paravalues' => $averages,
            'para' => $columnParameters->values()->all(),
        ]];
    }


    public function getMultiAxisChartData($id)
    {
        $device = Device::with([
            'deviceType.deviceParameters' => function ($query) {
                $query->orderBy('order');
            },
            'deviceComponents',
        ])->findOrFail($id);

        $context = $this->buildComponentContext($device, $device->deviceComponents);
        $parameters = $context['device_type_parameters'];

        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays(7)->startOfDay();

        $readings = $this->fetchDeviceReadings($device, $start, $end, 5000);

        $days = collect(range(7, 0))->map(fn ($offset) => Carbon::now()->subDays($offset)->startOfDay());
        $categories = $days->map(fn (Carbon $day) => $day->format('m/d'))->values()->all();

        $grouped = $readings->groupBy(fn (DeviceParametersValues $reading) => $reading->read_at?->format('Y-m-d'));

        $series = $parameters->map(function ($parameter) use ($days, $grouped) {
            $code = $parameter->code ?? $parameter->name;

            return $days->map(function (Carbon $day) use ($code, $grouped) {
                $key = $day->format('Y-m-d');

                $values = ($grouped[$key] ?? collect())
                    ->map(fn (DeviceParametersValues $reading) => $reading->decoded_parameters[$code] ?? null)
                    ->filter(fn ($value) => is_numeric($value));

                if ($values->isEmpty()) {
                    return 0;
                }

                return round($values->avg(), 2);
            })->values()->all();
        })->values()->all();

        return [$series, $categories];
    }


    public function getGaugeWithBandsData($id)
    {
        $device = Device::with([
            'deviceType.deviceParameters' => function ($query) {
                $query->orderBy('order');
            },
            'deviceComponents',
        ])->findOrFail($id);

        $context = $this->buildComponentContext($device, $device->deviceComponents);
        $parameters = $context['device_type_parameters'];

        if ($parameters->isEmpty()) {
            return [0];
        }

        $targetParameter = $parameters->firstWhere('code', 'Temperature') ?? $parameters->first();
        $code = $targetParameter->code ?? $targetParameter->name;

        $readings = $this->fetchDeviceReadings($device, Carbon::now()->subDay()->startOfDay(), null, 1440);

        $values = $readings
            ->map(fn (DeviceParametersValues $reading) => $reading->decoded_parameters[$code] ?? null)
            ->filter(fn ($value) => is_numeric($value));

        $value = $values->isNotEmpty() ? round($values->avg(), 2) : 0;

        return [$value];
    }


    public function add_device_setting_values($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.device.add_device_setting_values', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_setting_values(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $devSet = DeviceSettingPerDevice::firstOrNew(['device_id' => $id]);

        $settings = [];
        foreach ($device->deviceType->deviceSettings as $setting) {
            $settings[$setting->code] = $request[$setting->name] ?? "0";
        }

        $devSet->settings = json_encode($settings);
        $devSet->save();

        return redirect()->route('admin.devices.show', $id)->with('success', 'Data updated successfully');
    }

    private function buildComponentContext(Device $device, ?Collection $existingComponents = null): array
    {
        $deviceComponents = $existingComponents ?? $device->deviceComponents()->with('component')->get();
        $deviceComponents = $deviceComponents->sortBy('order')->values();

        $deviceType = $device->deviceType;
        $deviceTypeParameters = $deviceType?->deviceParameters ?? collect();
        $deviceTypeParameters = collect($deviceTypeParameters)
            ->sortBy(fn ($parameter) => $parameter->pivot->order ?? 0)
            ->values();
        $firstParameter = $deviceTypeParameters->first();

        $componentSettings = $deviceComponents->mapWithKeys(function (DevicesComponents $component) {
            return [
                $component->component_id => [
                    'model' => $component,
                    'settings' => $this->decodeComponentSettings($component),
                ],
            ];
        });

        $lineSettings = $componentSettings[9]['settings'] ?? [];
        $columnSettings = $componentSettings[6]['settings'] ?? [];
        $tableSettings = $componentSettings[13]['settings'] ?? [];

        $collectParameterIds = static function (array $settings): Collection {
            return collect($settings['parameters'] ?? [])
                ->map(fn ($value) => (int) $value)
                ->filter()
                ->values();
        };

        $lineParameterIds = $collectParameterIds($lineSettings);
        $columnParameterIds = $collectParameterIds($columnSettings);
        $tableParameterIds = $collectParameterIds($tableSettings);

        $allParameterIds = $lineParameterIds
            ->merge($columnParameterIds)
            ->merge($tableParameterIds)
            ->unique()
            ->values();

        $parameterDefinitions = $allParameterIds->isNotEmpty()
            ? DeviceParameters::query()
                ->select(['id', 'code', 'name', 'unit'])
                ->whereIn('id', $allParameterIds)
                ->get()
                ->keyBy('id')
            : collect();

        $resolveParameters = static function (Collection $ids, Collection $definitions, Collection $fallback): Collection {
            $params = $ids->map(fn ($id) => $definitions->get($id))->filter()->values();

            return $params->isNotEmpty() ? $params : $fallback->values();
        };

        $lineParameters = $resolveParameters($lineParameterIds, $parameterDefinitions, $deviceTypeParameters);
        $columnParameters = $resolveParameters($columnParameterIds, $parameterDefinitions, collect());
        $tableParameters = $resolveParameters($tableParameterIds, $parameterDefinitions, $deviceTypeParameters);

        return [
            'device_components' => $deviceComponents,
            'device_type' => $deviceType,
            'device_type_parameters' => $deviceTypeParameters,
            'first_parameter' => $firstParameter,
            'line_settings' => $lineSettings,
            'column_settings' => $columnSettings,
            'table_settings' => $tableSettings,
            'line_parameters' => $lineParameters->values(),
            'column_parameters' => $columnParameters->values(),
            'table_parameters' => $tableParameters->values(),
            'number_of_row' => (int) ($tableSettings['number_of_row'] ?? 0),
            'parameter_definitions' => $parameterDefinitions,
        ];
    }

    private function buildSeries(Collection $readings, Collection $parameters): array
    {
        return $parameters
            ->map(function ($parameter) use ($readings) {
                $code = $parameter->code ?? $parameter->name;

                return $readings
                    ->map(fn (DeviceParametersValues $reading) => $reading->decoded_parameters[$code] ?? 0)
                    ->values()
                    ->all();
            })
            ->values()
            ->all();
    }

    private function computeParameterStatus(Collection $parameters, array $latestValues, ?LimitValues $limitValues, Collection $typeParameters): array
    {
        $colors = [];
        $dangerColors = [];
        $warningCount = 1;

        $minValues = $limitValues?->min_value ?? [];
        $maxValues = $limitValues?->max_value ?? [];
        $checkMin = (bool) ($limitValues?->min_warning ?? false);
        $checkMax = (bool) ($limitValues?->max_warning ?? false);

        foreach ($parameters as $index => $parameter) {
            $typeParameter = $typeParameters->firstWhere('code', $parameter->code);
            $pivotColor = $typeParameter && $typeParameter->pivot ? $typeParameter->pivot->color : null;
            $colors[$index] = $pivotColor ?? '#00989d';
            $dangerColors[$index] = '#000000';

            if (!array_key_exists($parameter->code, $latestValues)) {
                continue;
            }

            $value = $latestValues[$parameter->code];
            $minLimit = $checkMin ? ($minValues[$parameter->code] ?? null) : null;
            $maxLimit = $checkMax ? ($maxValues[$parameter->code] ?? null) : null;

            $breachMin = $minLimit !== null && $value < $minLimit;
            $breachMax = $maxLimit !== null && $value > $maxLimit;

            if ($breachMin || $breachMax) {
                $warningCount++;
                $dangerColors[$index] = 'red';
            }
        }

        return [$colors, $dangerColors, $warningCount];
    }

    private function resolveRangeColor(DeviceParameters $parameter, float|int $value): string
    {
        $ranges = $parameter->relationLoaded('parameterRangeColors')
            ? $parameter->parameterRangeColors
            : $parameter->parameterRangeColors()->get();

        foreach ($ranges as $range) {
            if ($value >= $range->from && $value < $range->to) {
                return $range->color ?? '#00989d';
            }
        }

        return '#00989d';
    }

    private function resolveLabel(int $from, int $to): int
    {
        if ($from === 1 && $to === 0) {
            return 1;
        }

        if ($from === 7 && $to === 0) {
            return 7;
        }

        if ($from === 30 && $to === 0) {
            return 30;
        }

        return 2;
    }

    private function decodeComponentSettings(?DevicesComponents $component, array $default = []): array
    {
        if (!$component || !$component->settings) {
            return $default;
        }

        $decoded = json_decode($component->settings, true);

        return is_array($decoded) ? $decoded : $default;
    }

    private function normalizeParameters($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return (array) $value;
    }

    private function fetchDeviceReadings(Device $device, ?Carbon $from = null, ?Carbon $to = null, int $limit = 720): Collection
    {
        $query = DeviceParametersValues::query()
            ->select(['id', 'device_id', 'parameters', 'time_of_read'])
            ->where('device_id', $device->id)
            ->whereNotNull('time_of_read');

        if ($from) {
            $query->where('time_of_read', '>=', $from);
        }

        if ($to) {
            $query->where('time_of_read', '<=', $to);
        }

        $readings = $query
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        return $readings
            ->map(function (DeviceParametersValues $reading) {
                $reading->decoded_parameters = $this->normalizeParameters($reading->parameters);
                $reading->read_at = $reading->time_of_read instanceof Carbon
                    ? $reading->time_of_read->copy()
                    : Carbon::parse($reading->time_of_read);

                return $reading;
            })
            ->sortBy('read_at')
            ->values();
    }

    private function resolveDateRange(?int $from, ?int $to): array
    {
        if ($from === null && $to === null) {
            return ['start' => null, 'end' => null];
        }

        if (($from ?? 0) < 0 && ($to ?? 0) < 0) {
            return ['start' => null, 'end' => null];
        }

        $now = Carbon::now();
        $fromDays = max(0, (int) ($from ?? 0));
        $toDays = max(0, (int) ($to ?? 0));

        $startDays = max($fromDays, $toDays);
        $endDays = min($fromDays, $toDays);

        $start = $now->copy()->subDays($startDays)->startOfDay();
        $end = $now->copy()->subDays($endDays)->endOfDay();

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $types = DeviceType::all();
        return view('admin.device.edit', compact('device', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceRequest $request, $id)
    {
        $device = Device::findOrFail($id);
        $originalOwner = $device->user_id;
        $device->name = $request->name;
        $device->device_id = $request->device_id;
        $user = auth()->user();
        $device->user_id = $user->id;
        $device->time_between_two_read = $request->time_between_two_read;
        $device->type_id = $request->type;
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;

        $device->tolerance = $request->tolerance;
        if ($device->save()) {
            $this->forgetSidebarCache($user?->id);
            if ($originalOwner && $originalOwner !== $user->id) {
                $this->forgetSidebarCache($originalOwner);
            }

            if (auth()->user()->role == 'Administrator') {
                return redirect()->route('admin.devices')->with('success', 'Data added successfully');
            }

            return redirect()->route('admin.devices.get_devices')->with('success', 'Data added successfully');
        }

        return redirect()->route('admin.devices.edit')->with('error', 'Data failed to add');
    }

    public function location($id)
    {
        $device = Device::findOrFail($id);

        return view('admin.device.location', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_location(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->longitude = $request->longitude;
        $device->latitude = $request->latitude;
        if ($device->save()) {
            return redirect()->route('admin.devices', $id)->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.devices.location', $id)->with('error', 'Data failed to updated');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $owner = $device->user_id;

        if ($device->delete()) {
            $this->forgetSidebarCache($owner);
            if (auth()->user()->role === 'Administrator') {
                return redirect()->route('admin.devices')->with('success', 'Data deleted successfully');
            }

            return redirect()->route('admin.devices.get_devices')->with('success', 'Data deleted successfully');
        }

        return redirect()->route('admin.devices')->with('error', 'Data failed to delete');
    }

    public function add_device_limit_values($id)
    {
        $device = Device::with('deviceType.deviceParameters')->findOrFail($id);

        return view('admin.device.add_device_limit_values', compact('device'));
    }

    public function add_limit_values(Request $request, $id)
    {
        $device = Device::with('deviceType.deviceParameters')->findOrFail($id);

        $rules = [];
        foreach ($device->deviceType->deviceParameters as $parameter) {
            $rules[$parameter->code . '_max'] = ['required', 'numeric'];
            $rules[$parameter->code . '_min'] = ['required', 'numeric'];
        }

        \Validator::make($request->all(), $rules)->validate();

        $limitValues = LimitValues::firstOrNew(['device_id' => $device->id]);

        $mins = [];
        $maxes = [];
        foreach ($device->deviceType->deviceParameters as $parameter) {
            $code = $parameter->code;
            $mins[$code] = $request->input($code . '_min', 0);
            $maxes[$code] = $request->input($code . '_max', 0);
        }

        $limitValues->min_warning = $request->boolean('min_warning');
        $limitValues->max_warning = $request->boolean('max_warning');
        $limitValues->min_value = json_encode($mins);
        $limitValues->max_value = json_encode($maxes);
        $limitValues->save();

        $this->forgetSidebarCache($device->user_id);

        return redirect()->route('admin.devices')->with('success', 'Data updated successfully');
    }

    public function export($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.device.export', compact('device'));
    }

    public function exportToDatasheet(Request $request)
    {
        $validated = $request->validate([
            'from' => ['required'],
            'to' => ['required'],
            'id' => ['required', 'integer', 'exists:devices,id'],
        ]);

        $from = $validated['from'];
        $to = $validated['to'];
        $dev = $validated['id'];
        return Excel::download(new ParametersDataExport($from, $to, $dev), 'parameter.xlsx');
    }

    private function forgetSidebarCache($userId): void
    {
        if ($userId) {
            Cache::forget(sprintf('website-user-%d', $userId));
        }
    }
}
