<?php

namespace App\Console\Commands;

use App\Models\FilesParametersValues;
use App\Models\FtpFile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use \Illuminate\Support\Facades\File;

class ReadFtpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:ftpFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read files from ftpFolder when uploaded from ftp server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = File::files(public_path('ftpfiles'));

        $allFiles = [];
        foreach ($files as $file) {
            $newFile = FtpFile::where('name', $file->getFilenameWithoutExtension())->first();
            if ($newFile == null) {
                $newFile = new FtpFile();
                $newFile->name = $file->getFilenameWithoutExtension();
                $newFile->extension = $file->getExtension();
                $newFile->save();
            }
            $fileContents = File::get($file);
            $lines = explode("\n", $fileContents);


            $filecontent = [];
            if (count($lines) == 1) {
                foreach ($lines as $line) {
                    $replacedString = str_replace("\r", "|", $line);
                    $array = explode("|", $replacedString);
                    $firstline = explode(",", trim($array[0], " "));
                    $firstline = array_map('trim', $firstline);

                    $replacedFirstline = str_replace("YYYY-MM-DD hh:mm:ss", "date", $firstline);
                    $isFirstLine = true;
                    foreach ($array as $key => $data) {
                        if ($isFirstLine) {
                            $isFirstLine = false;
                            continue; // Skip the first line
                        }
                        $line = explode(",", $data);
                        if (count($line) == count($replacedFirstline)) {
                            $replacedArray = array_combine($replacedFirstline, array_values($line));
                            $newParameters = new FilesParametersValues();
                            $newParameters->file_id = $newFile->id;
                            $newParameters->parameters = $replacedArray;
                            $newParameters->time_of_read = $replacedArray['date'];
                            $newParameters->save();
                            array_push($filecontent, $replacedArray);
                        }
                    }
                }
                $oldFilePath = public_path('oldFtpFiles') . '/' . $file->getFilename();
                File::move($file, $oldFilePath);
                array_push($allFiles, $filecontent);
            } else {
                $firstline = explode(",", trim($lines[0], " "));
                $firstline = array_map('trim', $firstline);
                $format = 'j/n/Y H:i';
                $replacedFirstline = str_replace("YYYY-MM-DD hh:mm:ss", "date", $firstline);
                $isFirstLine = true;
                foreach ($lines as $key => $data) {
                    if ($isFirstLine) {
                        $isFirstLine = false;
                        continue; // Skip the first line
                    }
                    $line = explode(",", $data);
                    if (count($line) == count($replacedFirstline)) {
                        $replacedArray = array_combine($replacedFirstline, array_values($line));

                        if ($replacedArray['Flow'] != ""){
                            $newParameters = new FilesParametersValues();
                            $newParameters->file_id = $newFile->id;
                            $newParameters->parameters = $replacedArray;
                            $dateTime = DateTime::createFromFormat($format, $replacedArray['date']);
                            if ($dateTime != false){
                                $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
                            }else{
                                $formattedDateTime =$replacedArray['date'];
                            }
                            $newParameters->time_of_read = $formattedDateTime;
                            $newParameters->save();
                            array_push($filecontent, $replacedArray);
                        }

                    }
                }
                $oldFilePath = public_path('oldFtpFiles') . '/' . $file->getFilename();
                File::move($file, $oldFilePath);
                array_push($allFiles, $filecontent);
            }

        }
    }
}
