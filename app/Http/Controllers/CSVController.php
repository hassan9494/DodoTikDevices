<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as FtpAdapter;

class CSVController extends Controller
{
    public function store(Request $request)
    {
        $config = [
            'host' => 'FTP_HOST',
            'username' => 'FTP_USERNAME',
            'password' => 'FTP_PASSWORD',
            'port' => 21,
            'root' => '/',
        ];

        $adapter = new FtpAdapter($config);
        $filesystem = new Filesystem($adapter);

        $files = $filesystem->listContents('/');

        foreach ($files as $file) {
            if ($file['type'] === 'file') {
                $contents = $filesystem->read($file['path']);

                // Process the CSV file and save data to the database
                $this->processCSVFile($contents);
            }
        }

        // Redirect the user back or return a response
    }

    private function processCSVFile(string $fileContents)
    {
        // Read the CSV contents and process the data
        // Your logic here to save the data to the database
    }
}
