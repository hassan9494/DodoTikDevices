<?php

namespace Tests\Feature\Admin;

use App\Models\General;
use App\Models\User;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GeneralSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_general_settings_page(): void
    {
        $this->seed(TestDataSeeder::class);
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.general'));

        $response->assertOk();
        $response->assertViewIs('admin.general');
        $response->assertViewHas('general', fn ($general) => $general instanceof General);
    }

    public function test_admin_can_update_general_settings(): void
    {
        Storage::fake('public');

        $this->seed(TestDataSeeder::class);
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $payload = [
            'title' => 'Updated Title',
            'address1' => '123 Main St',
            'address2' => 'Suite 400',
            'phone' => '+1-555-0100',
            'email' => 'new-email@example.com',
            'twitter' => '@updated',
            'facebook' => 'facebook.com/updated',
            'instagram' => 'instagram.com/updated',
            'linkedin' => 'linkedin.com/company/updated',
            'footer' => 'Updated footer',
            'gmaps' => 'https://maps.example.com',
            'tawkto' => 'https://tawk.to/widget',
            'disqus' => 'updated-disqus',
            'sharethis' => 'updated-sharethis',
            'gverification' => 'verification-code',
            'keyword' => 'iot, telemetry',
            'meta_desc' => 'Updated description',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'favicon' => UploadedFile::fake()->image('favicon.png'),
        ];

        $response = $this->actingAs($admin)->post(route('admin.general.update'), $payload);

        $response->assertRedirect(route('admin.general'));
        $response->assertSessionHas('success');

        $general = General::first();

        $this->assertEquals('Updated Title', $general->title);
        $this->assertEquals('123 Main St', $general->address1);
        $this->assertEquals('Suite 400', $general->address2);
        $this->assertEquals('+1-555-0100', $general->phone);
        $this->assertEquals('new-email@example.com', $general->email);
        $this->assertEquals('@updated', $general->twitter);
        $this->assertEquals('facebook.com/updated', $general->facebook);
        $this->assertEquals('instagram.com/updated', $general->instagram);
        $this->assertEquals('linkedin.com/company/updated', $general->linkedin);
        $this->assertEquals('Updated footer', $general->footer);
        $this->assertEquals('https://maps.example.com', $general->gmaps);
        $this->assertEquals('https://tawk.to/widget', $general->tawkto);
        $this->assertEquals('updated-disqus', $general->disqus);
        $this->assertEquals('updated-sharethis', $general->sharethis);
        $this->assertEquals('verification-code', $general->gverification);
        $this->assertEquals('iot, telemetry', $general->keyword);
        $this->assertEquals('Updated description', $general->meta_desc);

        Storage::disk('public')->assertExists($general->logo);
        Storage::disk('public')->assertExists($general->favicon);
    }
}
