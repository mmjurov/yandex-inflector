<?php
//use PSR-0 autoloader instead of this
include 'src/Yandex/Inflector/Cache/Provider.php';
include 'src/Yandex/Inflector/Cache/Bitrix.php';
include 'src/Yandex/Inflector/Client.php';

$obInflect = new \Yandex\Inflector\Client();

//Склонить слово
$obInflect->inflect('Слово');

//Получим все склонения слова в массиве
var_dump($obInflect->getInflections());

//Получим дательный падеж
var_dump($obInflect->getDative());