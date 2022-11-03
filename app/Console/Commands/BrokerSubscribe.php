<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use PhpMqtt\Client\Facades\MQTT;

class BrokerSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broker:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe A Topic On Broker';

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
        // $data = array('name'=>"Virat Gandhi");
        // Mail::send(['text'=>'email.test'], $data, function($message) {
        //     $message->to('hassanalwan36@gmail.com', 'Tutorials Point')->subject
        //     ('Laravel Basic Testing Mail');
        //     $message->from('info@dodolora.com','DODOTIK');
        // });
        $mqtt = MQTT::connection();
        // $mqtt->subscribe('some/topic');
        $mqtt->publish('DODOLORA', 'Dodolora', 2, true); // Retain the message
//        $mqtt->loop(true);
    }
}
