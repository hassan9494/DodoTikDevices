<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Models\{About, Device, DeviceParametersValues, DeviceType, General, TestApi, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpMqtt\Client\Facades\MQTT;

class GeneralController extends Controller
{
    public function dashboard()
    {

        try {
            $user = auth()->user();
            $referenceTime = Carbon::now();

            $adminCount = User::count();

            $deviceLimit = (int) config('dashboard.device_limit', 200);

            $baseQuery = Device::query()
                ->select([
                    'devices.id',
                    'devices.name',
                    'devices.type_id',
                    'devices.user_id',
                    'devices.latitude',
                    'devices.longitude',
                    'devices.time_between_two_read',
                    'devices.tolerance',
                ])
                ->visibleTo($user);

            $totalDevices = (clone $baseQuery)->toBase()->count();

            $deviceRows = $baseQuery
                ->orderByDesc('devices.id')
                ->limit($deviceLimit + 1)
                ->get();

            $hasMoreDevices = $deviceRows->count() > $deviceLimit;
            if ($hasMoreDevices) {
                $deviceRows = $deviceRows->take($deviceLimit);
            }

            $deviceIds = $deviceRows->pluck('id')->all();
            $typeIds = $deviceRows->pluck('type_id')->filter()->unique()->all();

            $types = empty($typeIds)
                ? collect()
                : DeviceType::query()
                    ->select(['id', 'name'])
                    ->whereIn('id', $typeIds)
                    ->get();

            $parametersByType = empty($typeIds)
                ? collect()
                : DB::table('device_parameters as dp')
                    ->join('device_parameters_device_type as pivot', 'dp.id', '=', 'pivot.device_parameters_id')
                    ->select(['pivot.device_type_id as type_id', 'dp.code', 'dp.name', 'dp.unit'])
                    ->whereIn('pivot.device_type_id', $typeIds)
                    ->get()
                    ->groupBy('type_id');

            $limitsByDevice = empty($deviceIds)
                ? collect()
                : DB::table('limit_values')
                    ->select(['device_id', 'min_warning', 'max_warning', 'min_value', 'max_value'])
                    ->whereIn('device_id', $deviceIds)
                    ->get()
                    ->keyBy('device_id');

            $lastReadings = empty($deviceIds)
                ? collect()
                : DB::table('device_parameters_values as dpv')
                    ->select(['dpv.id', 'dpv.device_id', 'dpv.parameters', 'dpv.time_of_read'])
                    ->join(DB::raw('(SELECT MAX(id) as max_id, device_id FROM device_parameters_values WHERE device_id IN (' . implode(',', $deviceIds) . ') GROUP BY device_id) as latest'), function ($join) {
                        $join->on('dpv.id', '=', 'latest.max_id');
                    })
                    ->get()
                    ->keyBy('device_id');

            $referenceTime = Carbon::now();

            $payloadSummary = $this->buildDashboardPayload(
                $deviceRows,
                $types,
                $parametersByType,
                $limitsByDevice,
                $lastReadings,
                $referenceTime
            );

            return view('admin.dashboard', [
                'types' => $payloadSummary['types'],
                'adminCount' => $adminCount,
                'centroid' => $payloadSummary['centroid'],
                'devicesPayload' => $payloadSummary['devicesPayload'],
                'devicesByType' => $payloadSummary['devicesByType'],
                'untypedDevices' => $payloadSummary['untypedDevices'],
                'totalDevices' => $totalDevices,
                'displayedDevices' => $payloadSummary['displayedCount'],
                'deviceLimit' => $deviceLimit,
                'hasMoreDevices' => $hasMoreDevices,
            ]);

        } catch (Exception $exception) {
            $error = $exception;
            return view('admin.error',compact('error'));
        }
    }

    public function device_status()
    {

        $user = auth()->user();
        $now = Carbon::now();
        if ($user->role == 'Administrator') {
            $admin = User::orderBy('id', 'desc')->count();
            $devices = Device::all();
        } else {
            $admin = User::orderBy('id', 'desc')->count();
            $devices = Device::where('user_id', $user->id)->get();
        }
        $types = DeviceType::all();
        $state = [];
        $status = "Offline";
        foreach ($devices as $key => $device) {
            $parameters = $device->deviceParameters;
            $lastPara = DeviceParametersValues::where('device_id', $device->id)->orderBy('id', 'desc')->first();
            if (count($parameters) > 0) {
                if ($now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->m == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->d == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->h == 0 && $now->diff(date("m/d/Y H:i", strtotime($lastPara->time_of_read)))->i < ($device->time_between_two_read + $device->tolerance)) {
                    $status = "Online";
                } else {
                    $status = "Offline";
                }
            } else {
                $status = "Offline";
            }
            array_push($state, $status,);

        }

        return array($state, $devices, $types);
    }

    public function documentaion()
    {
        $general = General::find(1);
//        $test = json_decode("{\"applicationID\":\"4\",\"applicationName\":\"Test_raed\",\"deviceName\":\"FireAlarm\",\"devEUI\":\"oNt\/d0mAth8=\",\"rxInfo\":[],\"txInfo\":{\"frequency\":867300000,\"modulation\":\"LORA\",\"loRaModulationInfo\":{\"bandwidth\":125,\"spreadingFactor\":9,\"codeRate\":\"4\/5\",\"polarizationInversion\":false}},\"adr\":false,\"dr\":3,\"fCnt\":1,\"fPort\":2,\"data\":\"AZUAAQwJCAAhARgBoQAAfCEt\",\"objectJSON\":\"{}\",\"tags\":{},\"confirmedUplink\":false,\"devAddr\":\"ALc9wg==\"}");

        return view('admin.documentaion', compact('general'));
    }

    public function testData()
    {
//        $data = "$$0128AA865740039901280|50811800230305111930391300000000000000000000000008496665082834500000.800000003158.5066N03554.6835E000850";
        $test = "$$0116AA359960105015707|10001000230306112113422500000000000004BB976A102100000000.700000002543.2241N05129.7910E040220";

        $s='$$0030CF8642440230279140110107';

// Input string. Checksum to be generated over the first 10 elements.
        $string = '3599601050149';

// Initial checksum
        $checksum = 0;

// Split into chunks and process first 10 parts
        $parts = $string;
        $nLength =  Str::length($parts);
        for ($i = 0; $i < $nLength; $i++) {
            $part = $parts[$i];
            $nr = hexdec($part);
            $checksum ^= $nr;
        }

// Done, bring back checksum into 0..0xff range
        $checksum &= 0xff;
        echo "Got checksum: ", $checksum, "\n";
        dd($checksum);

        $allData = TestApi::all();
        return view('admin.test1',compact('allData'));
    }

    public function general()
    {
        $general = General::find(1);
        return view('admin.general', [
            'general' => $general
        ]);
    }

    public function generalUpdate(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'address1' => ['required', 'string'],
            'address2' => ['nullable', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'twitter' => ['nullable', 'string'],
            'facebook' => ['nullable', 'string'],
            'instagram' => ['nullable', 'string'],
            'linkedin' => ['nullable', 'string'],
            'footer' => ['required', 'string'],
            'gmaps' => ['required', 'string'],
            'tawkto' => ['nullable', 'string'],
            'disqus' => ['nullable', 'string'],
            'sharethis' => ['nullable', 'string'],
            'gverification' => ['nullable', 'string'],
            'keyword' => ['nullable', 'string'],
            'meta_desc' => ['nullable', 'string'],
            'logo' => ['nullable', 'image'],
            'favicon' => ['nullable', 'image'],
        ]);

        $general = General::find(1);

        $general->title = $validated['title'];
        $general->address1 = $validated['address1'];
        $general->address2 = $validated['address2'] ?? null;
        $general->phone = $validated['phone'];
        $general->email = $validated['email'];
        $general->twitter = $validated['twitter'] ?? null;
        $general->facebook = $validated['facebook'] ?? null;
        $general->instagram = $validated['instagram'] ?? null;
        $general->linkedin = $validated['linkedin'] ?? null;
        $general->footer = $validated['footer'];
        $general->gmaps = $validated['gmaps'];
        $general->tawkto = $validated['tawkto'] ?? null;
        $general->disqus = $validated['disqus'] ?? null;
        $general->sharethis = $validated['sharethis'] ?? null;
        $general->gverification = $validated['gverification'] ?? null;
        $general->keyword = $validated['keyword'] ?? null;
        $general->meta_desc = $validated['meta_desc'] ?? null;

        if ($request->hasFile('logo')) {
            if ($general->logo && Storage::disk('public')->exists($general->logo)) {
                Storage::disk('public')->delete($general->logo);
            }

            $general->logo = $request->file('logo')->store('images/general', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($general->favicon && Storage::disk('public')->exists($general->favicon)) {
                Storage::disk('public')->delete($general->favicon);
            }

            $general->favicon = $request->file('favicon')->store('images/general', 'public');
        }
        if ($general->save()) {

            return redirect()->route('admin.general')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.general')->with('error', 'Data failed to update');

        }
    }

    public function about()
    {
        $about = About::find(1);
        return view('admin.about', [
            'about' => $about
        ]);
    }

    public function aboutUpdate(Request $request)
    {
        $about = About::find(1);
        $about->title = $request->title;
        $about->subject = $request->subject;
        $about->desc = $request->desc;

        if ($about->save()) {

            return redirect()->route('admin.about')->with('success', 'Data updated successfully');

        } else {

            return redirect()->route('admin.about')->with('error', 'Data failed to update');

        }

    }

//    public function dashboard()
//    {
//        try {
//            $user = auth()->user();
//            $now = Carbon::now();
//
//            $admin = User::orderBy('id', 'desc')->count();
//
//            if ($user->role == 'Administrator') {
//                $devices = Device::with('deviceType', 'limitValues', 'deviceParameters')
//                    ->get();
//            } else {
//                $devices = Device::with('deviceType', 'limitValues', 'deviceParameters')
//                    ->where('user_id', $user->id)
//                    ->get();
//            }
//
//            $types = DeviceType::all();
//
//            $state = [];
//            $warning = [];
//            $lastMinDanger = [];
//            $lastdangerRead = [];
//            $long = 0;
//            $lat = 0;
//
//            foreach ($devices as $key => $device) {
//                $long += $device->longitude;
//                $lat += $device->latitude;
//                $warning[$key] = 0;
//                $lastMinDanger[$key] = null;
//                $lastdangerRead[$key] = array_fill(0, 20, "#000000");
//
//                $parameters = $device->deviceParameters;
//                $lastPara = $device->deviceParameters->last();
//
//                if (count($parameters) > 0) {
//                    $lastParaTime = strtotime($lastPara->time_of_read);
//                    $diff = $now->diff(Carbon::createFromTimestamp($lastParaTime));
//
//                    if ($diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i < ($device->time_between_two_read + $device->tolerance)) {
//                        $status = "Online";
//                    } else {
//                        $status = "Offline";
//                    }
//                } else {
//                    $status = "Offline";
//                }
//
//                $state[] = $status;
//
//                if ($device->deviceType != null) {
//                    foreach ($device->deviceType->deviceParameters as $key2 => $tPara) {
//                        if (isset($device->limitValues)) {
//                            if ($device->limitValues->min_warning == 1 && $lastPara != null) {
//                                $deviceParams = json_decode($lastPara->parameters, true);
//                                $limitValues = json_decode($device->limitValues->min_value, true);
//                                if (isset($deviceParams[$tPara->code]) && isset($limitValues[$tPara->code])) {
//                                    if ($deviceParams[$tPara->code] < $limitValues[$tPara->code]) {
//                                        $warning[$key] += 1;
//                                        $lastMinDanger[$key] = $lastPara;
//                                        $lastdangerRead[$key][$key2] = "red";
//                                    }
//                                }
//                            }
//
//                            if ($device->limitValues->max_warning == 1 && $lastPara != null) {
//                                $deviceParams = json_decode($lastPara->parameters, true);
//                                $limitValues = json_decode($device->limitValues->max_value, true);
//                                if (isset($deviceParams[$tPara->code]) && isset($limitValues[$tPara->code])) {
//                                    if ($deviceParams[$tPara->code] > $limitValues[$tPara->code]) {
//                                        $warning[$key] += 1;
//                                        $lastMinDanger[$key] = $lastPara;
//                                        $lastdangerRead[$key][$key2] = "red";
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//
//            $deviceCount = count($devices);
//            if ($deviceCount > 0) {
//                $long /= $deviceCount;
//                $lat /= $deviceCount;
//            }
//
//            return view('admin.dashboard', compact('types', 'admin', 'long', 'lat', 'lastdangerRead', 'devices', 'state', 'warning', 'lastMinDanger'));
//        } catch (Exception $exception) {
//            $error = $exception;
//            return view('admin.error', compact('error'));
//        }
//    }

    private function buildDashboardPayload($deviceRows, $types, $parametersByType, $limitsByDevice, $lastReadings, Carbon $referenceTime): array
    {
        $devicesPayload = [];
        $devicesByType = [];
        $untypedDevices = [];
        $centroid = ['lat' => 0.0, 'lng' => 0.0, 'count' => 0];

        foreach ($deviceRows as $device) {
            $type = $types->firstWhere('id', $device->type_id);
            $parameterDefs = $parametersByType->get($device->type_id, collect())->all();
            $limitRow = $limitsByDevice->get($device->id);
            $lastReading = $lastReadings->get($device->id);

            $payload = $this->buildDevicePayload(
                $device,
                $type,
                $parameterDefs,
                $limitRow,
                $lastReading,
                $referenceTime
            );

            $devicesPayload[] = $payload;

            if ($payload['type']) {
                $devicesByType[$payload['type']['id']][] = $payload;
            } else {
                $untypedDevices[] = $payload;
            }

            if ($payload['coordinates']['lat'] !== null && $payload['coordinates']['lng'] !== null) {
                $centroid['lat'] += (float) $payload['coordinates']['lat'];
                $centroid['lng'] += (float) $payload['coordinates']['lng'];
                $centroid['count']++;
            }
        }

        $avgLat = $centroid['count'] > 0 ? $centroid['lat'] / $centroid['count'] : null;
        $avgLng = $centroid['count'] > 0 ? $centroid['lng'] / $centroid['count'] : null;

        return [
            'types' => $types,
            'devicesPayload' => $devicesPayload,
            'devicesByType' => $devicesByType,
            'untypedDevices' => $untypedDevices,
            'displayedCount' => count($devicesPayload),
            'centroid' => ['lat' => $avgLat, 'lng' => $avgLng],
        ];
    }

    private function buildDevicePayload($device, $type, array $parameterDefs, $limitRow, $lastReading, Carbon $referenceTime): array
    {
        $typePayload = $type ? [
            'id' => $type->id,
            'name' => $type->name,
            'slug' => Str::slug($type->name),
        ] : null;

        $coordinates = [
            'lat' => $device->latitude,
            'lng' => $device->longitude,
        ];

        $status = 'Offline';
        $threshold = (int) ($device->time_between_two_read ?? 0) + (int) ($device->tolerance ?? 0);

        if ($lastReading && $lastReading->time_of_read && $threshold > 0) {
            $readAt = Carbon::parse($lastReading->time_of_read);
            if ($readAt->diffInMinutes($referenceTime) < $threshold) {
                $status = 'Online';
            }
        }

        $readingParameters = [];
        if ($lastReading && $lastReading->parameters) {
            $decoded = json_decode($lastReading->parameters, true);
            if (is_array($decoded)) {
                $readingParameters = $decoded;
            }
        }

        $minValues = [];
        $maxValues = [];
        $minWarning = false;
        $maxWarning = false;

        if ($limitRow) {
            $minValues = json_decode($limitRow->min_value ?? '', true) ?? [];
            $maxValues = json_decode($limitRow->max_value ?? '', true) ?? [];
            $minWarning = (bool) ($limitRow->min_warning ?? false);
            $maxWarning = (bool) ($limitRow->max_warning ?? false);
        }

        $parametersPayload = [];
        $warningCount = 0;

        foreach ($parameterDefs as $definition) {
            $code = $definition->code;
            $value = $readingParameters[$code] ?? null;
            $minLimit = $minWarning ? ($minValues[$code] ?? null) : null;
            $maxLimit = $maxWarning ? ($maxValues[$code] ?? null) : null;

            $isWarning = false;
            if ($value !== null) {
                if ($minLimit !== null && $value < $minLimit) {
                    $isWarning = true;
                }
                if ($maxLimit !== null && $value > $maxLimit) {
                    $isWarning = true;
                }
            }

            if ($isWarning) {
                $warningCount++;
            }

            $parametersPayload[] = [
                'code' => $code,
                'name' => $definition->name,
                'unit' => $definition->unit,
                'value' => $value,
                'color' => $isWarning ? 'red' : '#000000',
            ];
        }

        $readingTime = $lastReading && $lastReading->time_of_read
            ? Carbon::parse($lastReading->time_of_read)
            : null;

        return [
            'id' => $device->id,
            'name' => $device->name,
            'type' => $typePayload,
            'coordinates' => $coordinates,
            'status' => $status,
            'warning_count' => $warningCount,
            'parameters' => $parametersPayload,
            'last_reading' => $readingTime ? [
                'time' => $readingTime->toIso8601String(),
                'formatted_time' => $readingTime->copy()->setTimezone(config('app.dashboard_timezone', 'Europe/Istanbul'))->format('Y-d-m h:i a'),
                'diff_minutes' => $readingTime->diffInMinutes($referenceTime),
            ] : null,
        ];
    }
}
