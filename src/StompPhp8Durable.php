<?php

require __DIR__ . '/../vendor/autoload.php';

use Stomp\Broker\ActiveMq\Mode\DurableSubscription;
use Stomp\Client;
use Stomp\Network\Observer\Exception\HeartbeatException;
use Stomp\Network\Observer\ServerAliveObserver;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;
use Stomp\Network\Connection;
use Stomp\Network\Observer\HeartbeatEmitter;

class StompClient8 {

    private $emitter;
    private $stomp;
    private $queue = 'TEST.PHP.EXAMPLE.APP';
    private $producer;

    public function __construct(){
        $host = 'artemis-ext-01.etp.sm-soft.ru';
        $alternateHost = 'artemis-ext-02.etp.sm-soft.ru';
        $port = '61613';
        $login = 'testphp';
        $password = 'testphp';
        $heartbeat = 500;
        $url1 = 'tcp://' . $host . ':' . $port;
        $url2 = 'tcp://' . $alternateHost . ':' . $port;

        $client = new Client('failover://(' . $url1 . ',' . $url2 . ')?randomize=false');
        $client->getConnection()->setReadTimeout(0,250000);
        $client->setClientId("testid");
        $client->setLogin($login,$password);
        $client->setHeartbeat($heartbeat);

        $this->producer = new Client('failover://(' . $url1 . ',' . $url2 . ')?randomize=false');
        $this->producer->setClientId("testprodeucer");
        $this->producer->setLogin($login,$password);
        $this->producer->setHeartbeat($heartbeat);

        $this->emitter = new HeartbeatEmitter($client->getConnection());
        $client->getConnection()->getObservers()->addObserver($this->emitter);

        $this->stomp = new DurableSubscription($client,$this->queue,null,'auto','client');
        $this->stomp->activate();
    }

    public function run(){
        $emitter = $this->emitter;
        $stomp = $this->stomp;

        if (!$emitter->isEnabled()) {
	        echo 'The Server is not supporting heartbeats.';
	        //exit(1);
        } else {
	        echo sprintf('The Client tries to send heart beats every %d ms.', $emitter->getInterval() * 1000), PHP_EOL;
        }
        
        $this->send("A message!");
        $this->send("A message 2!");

        while (true) {
            $msg = $stomp->read();
            if ($msg != null) {
                echo "MESSAGE: " . $msg->body . PHP_EOL;

                //$stomp->ack($msg);
                //$this->send('This is an answer!');    
            }
            else{
                echo "No message here ";
            }
        }

    }

    public function send($msg)
    {
       $stomp = $this->stomp;

       //$stomp->begin();
       $this->producer->send($this->queue,new Message($msg));
       //$stomp->commit();
    }

}