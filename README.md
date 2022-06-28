Было найдено 3 варианта для реализации взаимодействия с Artemis
- Stomp Client - модуль из pcel, 
https://www.php.net/manual/ru/book.stomp.php
Проблема - работает только с PHP 7.4.
Также нет heartbeat, видимо придется писать самому.
Для запуска:
 - добавить (изменить) зависимость в index.php (require_once 'src/StompPhp74.php';)
 - скачать модуль https://pecl.php.net/package/stomp
 - добавить его в php/ext
 - добавить extension="путь-до-модуля" в php.ini

- Stomp PHP - библиотека для composer, есть на гитхабе
https://github.com/stomp-php
Проблема - не получается читать несколько сообщений в бесконечном цикле
как и не получается вызвать метод .ack()
Для установки нужен только composer

- ReactPHP/Stomp - библиотека для composer
https://github.com/friends-of-reactphp/stomp
Проблема - коммитов нет уже 2 года, не поддерживается
Однако все вроде работает стабильно
Для установки нужен только composer

Общая проблема для всех 3 - они не асинхронные 
(для асинхронности последней нужен react-php)