<?php

namespace Tests\Feature\Admin;

use App\Exports\FactoryDeviceValueExport;
use App\Models\Device;
use App\Models\DeviceFactory;
use App\Models\Factory;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class FactoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function seedAdmin(): User
    {
        $this->seed(TestDataSeeder::class);

        return User::where('email', 'admin@example.com')->firstOrFail();
    }

    public function test_admin_can_view_factory_index_and_show_pages(): void
    {
        $admin = $this->seedAdmin();
        $factory = Factory::firstOrFail();

        $indexResponse = $this->actingAs($admin)->get(route('admin.factories'));
        $indexResponse->assertOk()->assertViewIs('admin.factory.index');

        $showResponse = $this->actingAs($admin)->get(route('admin.factories.show', $factory->id));
        $showResponse->assertOk()->assertViewIs('admin.factory.show');
        $showResponse->assertViewHas('factory', fn ($viewFactory) => $viewFactory->id === $factory->id);
    }

    public function test_admin_can_attach_device_to_factory(): void
    {
        $admin = $this->seedAdmin();
        $factory = Factory::firstOrFail();

        $availableDevice = Device::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.factories.attach', $factory->id), [
            'device' => $availableDevice->id,
        ]);

        $response->assertRedirect(route('admin.factories'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('device_factories', [
            'factory_id' => $factory->id,
            'device_id' => $availableDevice->id,
            'is_attached' => true,
        ]);
    }

    public function test_admin_can_detach_device_from_factory(): void
    {
        $admin = $this->seedAdmin();
        $deviceFactory = DeviceFactory::firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.factories.detach', $deviceFactory->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFalse($deviceFactory->fresh()->is_attached);
    }

    public function test_factory_details_export_triggers_excel_download(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 2, 9));

        $admin = $this->seedAdmin();
        $deviceFactory = DeviceFactory::firstOrFail();

        Excel::fake();

        $response = $this->actingAs($admin)->get(route('admin.factories.export', $deviceFactory->id));

        $response->assertStatus(200);

        Excel::assertDownloaded('parameter.xlsx', function ($export) {
            return $export instanceof FactoryDeviceValueExport;
        });
    }
}
