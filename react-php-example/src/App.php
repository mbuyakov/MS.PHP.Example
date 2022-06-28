<?php

class App
{
    public function run()
    {
        //host and port for stomp connection
        $host = 'localhost';
        $port = '61613';
        $login = 'admin';
        $password = 'admin';

        $loop = React\EventLoop\Factory::create();
        $factory = new React\Stomp\Factory($loop);
        $client = $factory->createClient(array(
            'vhost' => $host . ":" . $port,
            'login' => $login,
            'passcode' => $password
        ));

        $client
            ->connect()
            ->then(function ($client) use ($loop) {
                $client->subscribe('/queue/MessageHandler', function ($frame) {
                    echo "Message received: {$frame->body}\n";
                });

                //$loop->addPeriodicTimer(1, function () use ($client) {
                //    $client->send('/topic/foo', 'le message');
                //});
            });

        $loop->run();
    }
}
