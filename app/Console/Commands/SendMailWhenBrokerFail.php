<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Support\Facades\Mail;

class SendMailWhenBrokerFail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMail:whenBrokerFail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail When Broker Fail';

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
        try {
            $mqtt = MQTT::connection();
            $mqtt->subscribe('DODOLORA',function (string $topic, string $message) {
                if ($message == null){
                     $data = array('name'=>"Broker Stop");
                     Mail::send(['text'=>'email.brokerStop'], $data, function($message) {
                         $message->to('hassanalwan36@gmail.com', 'Broker Stop')->subject
                         ('Broker Stop');
                         $message->from('info@dodolora.com','DODOTIK');
                     });
                }
                echo sprintf('Received QoS level 1 message on topic [%s]: %s', $topic, $message);
            }, 0);
            $mqtt->loop(false,false);
        }catch (Exception $e){
            $data = array('name'=>"Broker Stop");
            $mess = $e->getMessage();
            Mail::send(['text'=>'email.brokerStop',compact('mess')], $data, function($message) {
                $message->to('hassanalwan36@gmail.com', 'Broker Stop')->subject
                ('Broker Stop');
                $message->from('info@dodolora.com','DODOTIK');
            });
        }
    }
}
