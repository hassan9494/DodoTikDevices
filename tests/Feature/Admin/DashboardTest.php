<?php

namespace Tests\Feature\Admin;

use App\Models\Device;
use App\Models\User;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_seeded_device_information(): void
    {
        $this->seed(TestDataSeeder::class);

        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $device = Device::firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('devices', fn ($devices) => $devices->contains('id', $device->id));
        $response->assertViewHas('admin', User::count());
        $response->assertViewHas('types', fn ($types) => $types->isNotEmpty());
        $response->assertViewHas('state', fn ($state) => is_array($state) && count($state) > 0);
        $response->assertViewHas('warning', fn ($warning) => is_array($warning));
        $response->assertViewHas('lastdangerRead');
        $response->assertViewHas('lastMinDanger');
        $response->assertViewHas('long', fn ($long) => is_numeric($long));
        $response->assertViewHas('lat', fn ($lat) => is_numeric($lat));
    }
}
