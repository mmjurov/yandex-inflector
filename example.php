<?php
//use autoloader instead of this
include 'lib/YandexInflectorException.php';
include 'lib/YandexInflectorCache.php';
include 'lib/YandexInflectorBitrixCache.php';
include 'lib/YandexInflector.php';

use mmjurov\YandexInflector;

$obInflect = new YandexInflector();

//Склонить слово
$obInflect->inflect('Слово');

//Получим все склонения слова в массиве
var_dump($obInflect->getInflections());

//Получим дательный падеж
var_dump($obInflect->getDative());