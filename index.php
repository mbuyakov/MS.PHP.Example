<?php
    require_once 'src/StompPhp8Durable.php';
    require_once 'vendor/autoload.php';
    
    use StompClient8 as Startup;
  
    $app = new Startup();
    $app->run();

