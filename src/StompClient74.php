
<?php

class StompClient {

    private $stomp;
    private $queue = '/queue/requests';
    private $answerQueue = '/queue/answers';

    public function __construct() {
        $queue  = $this->queue;
        $host = 'localhost';
        $port = '61613';
        $login = 'admin';
        $password = 'admin';

        try {
            $this->stomp = new Stomp('tcp://' . $host . ":" . $port ,$login,$password);
        } catch(StompException $e) {
            die('Ошибка подключения: ' . $e->getMessage());
        }

        $this->stomp->subscribe($queue);

    }

    public function run(){
        echo "Server is online";

        while (true){
            if (!$this->stomp->hasFrame()){
                continue;
            }
        
            $frame = $this->stomp->readFrame();
            if ($frame){
                echo "MESSAGE: " . $frame->body . PHP_EOL;
                $this->stomp->ack($frame);
                $this->stomp->send($this->answerQueue,"This is an answer!");
            }
        }
    }

    public function send($msg){
        $queue = $this->answerQueue;
        $this->stomp->send($queue,$msg);
    }

}