<?php
    require_once 'src/App.php';
    require_once 'vendor/autoload.php';
    
    use App as Startup;
  
    $app = new Startup();
    $app->run();
