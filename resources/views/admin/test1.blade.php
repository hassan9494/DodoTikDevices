@extends('layouts.admin')


@section('content')

<?php
use PhpMqtt\Client\Facades\MQTT;

    $test = '';
try {
    $mqtt = MQTT::connection();
    $mqtt->subscribe('DODOLORA',function (string $topic, string $message) {
        $test = $message;
        if ($message == null){
            dd('11111111111111111');
        }else{
            echo $message ;
        }
//        dd($message);
        echo sprintf('Received QoS level 1 message on topic [%s]: %s', $topic, $test);
    }, 0);
    $mqtt->loop(false,false);
}catch (Exception $e){
    dd($e);
}



?>
@endsection
