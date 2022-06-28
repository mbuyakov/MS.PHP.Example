<?php

require __DIR__ . '/../vendor/autoload.php';

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Interop\Amqp\AmqpQueue;

$host = 'localhost';
$port = '5672';
$login = 'admin';
$password = 'admin';

// connect to AMQP broker at localhost
$factory = new AmqpConnectionFactory('amqp://admin:admin@localhost:5672');

$context = $factory->createContext();
$fooQueue = $context->createQueue('Amqp8');
$fooQueue->addFlag(AmqpQueue::FLAG_DURABLE);
$context->declareQueue($fooQueue);

$message = $context->createMessage('Hello world!');

$context->createProducer()->send($fooTopic, $message);
