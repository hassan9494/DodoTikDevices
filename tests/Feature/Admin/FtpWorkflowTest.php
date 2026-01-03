<?php

namespace Tests\Feature\Admin;

use App\Exports\FileParametersExport;
use App\Models\FtpFile;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\TestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class FtpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private string $ftpDirectory;
    private string $oldFtpDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ftpDirectory = public_path('ftpfiles');
        $this->oldFtpDirectory = public_path('oldFtpFiles');

        File::deleteDirectory($this->ftpDirectory);
        File::deleteDirectory($this->oldFtpDirectory);

        File::ensureDirectoryExists($this->ftpDirectory);
        File::ensureDirectoryExists($this->oldFtpDirectory);
    }

    private function seedAdmin(): User
    {
        $this->seed(TestDataSeeder::class);

        return User::where('email', 'admin@example.com')->firstOrFail();
    }

    public function test_admin_can_view_ftp_file_index_and_show_pages(): void
    {
        $admin = $this->seedAdmin();
        $ftpFile = FtpFile::firstOrFail();

        $indexResponse = $this->actingAs($admin)->get(route('admin.files'));
        $indexResponse->assertOk()->assertViewIs('admin.ftp_files.index');

        $showResponse = $this->actingAs($admin)->get(route('admin.files.show', $ftpFile->id));
        $showResponse->assertOk()->assertViewIs('admin.ftp_files.show');
        $showResponse->assertViewHas('ftpFile', fn ($viewFile) => $viewFile->id === $ftpFile->id);
    }

    public function test_admin_can_import_new_ftp_files_from_disk(): void
    {
        $admin = $this->seedAdmin();

        $filename = 'telemetry.csv';
        $content = "date,Flow,TOT1,TOT2,TOT3\n2025-01-01 12:00,10,20,30,40\n";
        File::put($this->ftpDirectory . DIRECTORY_SEPARATOR . $filename, $content);

        $response = $this->actingAs($admin)->get(route('admin.files.import'));

        $response->assertOk()->assertViewIs('admin.ftp_files.index');

        $this->assertDatabaseHas('ftp_files', [
            'name' => 'telemetry',
            'extension' => 'csv',
        ]);

        $this->assertFileDoesNotExist($this->ftpDirectory . DIRECTORY_SEPARATOR . $filename);
        $this->assertFileExists($this->oldFtpDirectory . DIRECTORY_SEPARATOR . $filename);
    }

    public function test_ftp_file_export_triggers_excel_download(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 3, 8));

        $admin = $this->seedAdmin();
        $ftpFile = FtpFile::firstOrFail();

        Excel::fake();

        $response = $this->actingAs($admin)->post(route('admin.files.exportToDatasheet'), [
            'from' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'to' => Carbon::now()->format('Y-m-d'),
            'id' => $ftpFile->id,
        ]);

        $response->assertStatus(200);

        Excel::assertDownloaded('parameter.xlsx', function ($export) {
            return $export instanceof FileParametersExport;
        });
    }
}
