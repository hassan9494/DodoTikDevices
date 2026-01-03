<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static where(string $string, $id)
 * @method static findOrFail(int $id)
 */
class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'device_id','user_id','type_id','tolerance','longitude','latitude','time_between_two_read'
    ];

    protected $guarded=[];
    protected $table = 'devices';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class,'type_id');
    }

    public function deviceSetting()
    {
        return $this->hasOne(DeviceSettingPerDevice::class,'device_id');
    }

    public function limitValues()
    {
        return $this->hasOne(LimitValues::class,'device_id');
    }

    public function deviceParameters()
    {
        return $this->hasMany(DeviceParametersValues::class,'device_id');
    }

    public function lastDeviceParameter()
    {
        return $this->hasOne(DeviceParametersValues::class, 'device_id')
            ->latest('time_of_read')
            ->latest('id');
    }

    public function deviceComponent()
    {
        return $this->hasOne(DeviceComponent::class,'device_id');
    }

    public function deviceComponents()
    {
        return $this->hasMany(DevicesComponents::class,'device_id');
    }

    public function deviceFactories()
    {
        return $this->hasMany(DeviceFactory::class,'device_id');
    }

    public function deviceFactoryValues()
    {
        return $this->hasMany(DeviceFactoryValue::class,'device_id');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->role === 'Administrator') {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function scopeWithDashboardRelations(Builder $query): Builder
    {
        return $query->with('deviceType:id,name');
    }

    public function isOnline(?Carbon $reference = null): bool
    {
        $reference ??= Carbon::now();
        $lastReading = $this->lastDeviceParameter;

        if (!$lastReading || !$lastReading->time_of_read) {
            return false;
        }

        $threshold = (int) ($this->time_between_two_read ?? 0) + (int) ($this->tolerance ?? 0);
        if ($threshold <= 0) {
            return false;
        }

        return $lastReading->time_of_read->diffInMinutes($reference) < $threshold;
    }

    public function warningSnapshot(array $parameterDefinitions = []): array
    {
        $lastReading = $this->lastDeviceParameter;
        $limits = $this->limitValues;

        $parameterValues = $lastReading?->parameters ?? [];
        $minValues = $limits?->min_value ?? [];
        $maxValues = $limits?->max_value ?? [];
        $checkMin = $limits?->min_warning ?? false;
        $checkMax = $limits?->max_warning ?? false;

        $warnings = [];

        foreach ($parameterDefinitions as $definition) {
            $code = $definition['code'];
            $value = $parameterValues[$code] ?? null;
            $minLimit = $checkMin ? ($minValues[$code] ?? null) : null;
            $maxLimit = $checkMax ? ($maxValues[$code] ?? null) : null;

            $isWarning = false;
            $triggeredBy = null;

            if ($value !== null) {
                if ($minLimit !== null && $value < $minLimit) {
                    $isWarning = true;
                    $triggeredBy = 'min';
                }

                if ($maxLimit !== null && $value > $maxLimit) {
                    $isWarning = true;
                    $triggeredBy = $triggeredBy === 'min' ? 'range' : 'max';
                }
            }

            if ($isWarning) {
                $warnings[] = [
                    'code' => $code,
                    'name' => $definition['name'] ?? $code,
                    'unit' => $definition['unit'] ?? null,
                    'value' => $value,
                    'min_limit' => $minLimit,
                    'max_limit' => $maxLimit,
                    'triggered_by' => $triggeredBy,
                ];
            }
        }

        return [
            'warning_count' => count($warnings),
            'warnings' => $warnings,
        ];
    }

    public function toDashboardPayload(?Carbon $reference = null, array $parameterDefinitions = []): array
    {
        $reference ??= Carbon::now();
        $snapshot = $this->warningSnapshot($parameterDefinitions);
        $lastReading = $this->lastDeviceParameter;
        $readingTime = $lastReading?->time_of_read;
        $formattedTime = $readingTime
            ? $readingTime->copy()->setTimezone(config('app.dashboard_timezone', 'Europe/Istanbul'))->format('Y-d-m h:i a')
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->deviceType ? [
                'id' => $this->deviceType->id,
                'name' => $this->deviceType->name,
                'slug' => Str::slug($this->deviceType->name),
            ] : null,
            'coordinates' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ],
            'status' => $this->isOnline($reference) ? 'Online' : 'Offline',
            'warning_count' => $snapshot['warning_count'],
            'warnings' => $snapshot['warnings'],
            'last_reading' => $lastReading ? [
                'time' => $readingTime?->toIso8601String(),
                'formatted_time' => $formattedTime,
                'diff_minutes' => $readingTime?->diffInMinutes($reference),
            ] : null,
        ];
    }
}



