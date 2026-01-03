<?php

namespace Tests\Feature\Admin;

use App\Exports\ParametersDataExport;
use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\DeviceType;
use App\Models\LimitValues;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class DeviceLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function seedAdmin(): User
    {
        $this->seed(TestDataSeeder::class);

        return User::where('email', 'admin@example.com')->firstOrFail();
    }

    public function test_admin_can_create_device(): void
    {
        $admin = $this->seedAdmin();
        $deviceType = DeviceType::firstOrFail();

        $payload = [
            'name' => 'QA Created Device',
            'device_id' => 'DEV-QA-' . strtoupper(substr(uniqid(), -6)),
            'type' => $deviceType->id,
            'tolerance' => 5,
            'time_between_two_read' => 15,
        ];

        $response = $this->actingAs($admin)->post(route('admin.devices.store'), $payload);

        $response->assertRedirect(route('admin.devices'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('devices', [
            'device_id' => $payload['device_id'],
            'name' => 'QA Created Device',
        ]);
    }

    public function test_admin_can_update_device(): void
    {
        $admin = $this->seedAdmin();
        $device = Device::firstOrFail();
        $deviceType = $device->deviceType;

        $payload = [
            'name' => 'Updated Device Name',
            'device_id' => $device->device_id,
            'type' => $deviceType->id,
            'tolerance' => 7,
            'time_between_two_read' => 20,
            'longitude' => 33.1234,
            'latitude' => 42.9876,
        ];

        $response = $this->actingAs($admin)->post(route('admin.devices.update', $device->id), $payload);

        $response->assertRedirect(route('admin.devices'));
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Device Name', $device->fresh()->name);
        $this->assertEquals(33.1234, (float) $device->fresh()->longitude);
    }

    public function test_admin_can_delete_device(): void
    {
        $admin = $this->seedAdmin();
        $deviceType = DeviceType::firstOrFail();
        $device = Device::factory()
            ->ownedBy($admin)
            ->forType($deviceType)
            ->create([
                'device_id' => 'DEV-DEL-' . strtoupper(substr(uniqid(), -6)),
            ]);

        $response = $this->actingAs($admin)->delete(route('admin.devices.destroy', $device->id));

        $response->assertRedirect(route('admin.devices'));
        $response->assertSessionHas('success');
        $this->assertModelMissing($device);
    }

    public function test_admin_can_configure_device_limit_values(): void
    {
        $admin = $this->seedAdmin();
        $device = Device::with('deviceType.deviceParameters', 'limitValues')->firstOrFail();
        $parameters = $device->deviceType->deviceParameters;

        $payload = [
            'min_warning' => '1',
            'max_warning' => '1',
        ];

        foreach ($parameters as $parameter) {
            $payload[$parameter->code . '_min'] = 11;
            $payload[$parameter->code . '_max'] = 77;
        }

        $response = $this->actingAs($admin)->post(route('admin.devices.add_limit_values', $device->id), $payload);

        $response->assertSessionHas('success');

        $limitValues = LimitValues::where('device_id', $device->id)->first();
        $this->assertNotNull($limitValues);
        $this->assertTrue($limitValues->min_warning);
        $this->assertTrue($limitValues->max_warning);

        $minValues = json_decode($limitValues->min_value, true);
        $maxValues = json_decode($limitValues->max_value, true);

        foreach ($parameters as $parameter) {
            $this->assertEquals(11, (int) ($minValues[$parameter->code] ?? 0));
            $this->assertEquals(77, (int) ($maxValues[$parameter->code] ?? 0));
        }
    }

    public function test_device_dashboard_view_displays_recent_parameters(): void
    {
        $admin = $this->seedAdmin();
        $device = Device::with('deviceType')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.devices.show', $device->id));

        $response->assertOk();
        $response->assertViewIs('admin.device.custom_show');
        $response->assertViewHas('device', fn ($viewDevice) => $viewDevice->id === $device->id);
        $response->assertViewHas('paraValues', fn ($values) => is_array($values));
        $response->assertViewHas('status', fn ($status) => in_array($status, ['Online', 'Offline'], true));
    }

    public function test_device_parameters_export_uses_excel_download(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 1, 12));

        $admin = $this->seedAdmin();
        $device = Device::firstOrFail();

        Excel::fake();

        $response = $this->actingAs($admin)->post(route('admin.devices.exportToDatasheet'), [
            'from' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'to' => Carbon::now()->format('Y-m-d'),
            'id' => $device->id,
        ]);

        $response->assertStatus(200);

        Excel::assertDownloaded('parameter.xlsx', function ($export) use ($device) {
            return $export instanceof ParametersDataExport;
        });
    }
}
