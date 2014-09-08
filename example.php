<?php
session_start();
require_once('YandexInflector.class.php');

$obInflect = new YandexInflector();

//Склонить слово
$obInflect->inflect('Слово');

//Получим все склонения слова в массиве
var_dump($obInflect->getInflections());

//Получим дательный падеж
var_dump($obInflect->getDative());