<?php 

require_once('YandexInflector.class.php');

$obInflect = new YandexInflector('Слово');

//Получим все склонения слова в массиве
var_dump($obInflect->getInflections());

//Получим дательный падеж
var_dump($obInflect->getDative());