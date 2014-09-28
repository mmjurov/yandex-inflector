<?php
namespace mmjurov;

/**
 * Абстрактный класс, который описывает все необходимые для реализации методы
 * Class YandexInflectorCache
 */
abstract class YandexInflectorCache
{
	/**
	 * Конструктор. Передаются на вход параметры, необходимые для работы класса кеша
	 * @param $options
	 */
	abstract function __construct($options);

	/**
	 * Метод подключения. Если требуется подключение к базе с кешем, то нужно реализовать его в этом методе
	 * @return boolean
	 */
	abstract function connect();

	/**
	 * Метод получения данных из кеша по ключу
	 * @param string $key
	 * @return mixed
	 */
	abstract function get($key);

	/**
	 * Метод установки значения в кеш по ключу
	 * @param string $key
	 * @param $value
	 * @return boolean
	 */
	abstract function set($key, $value);
}
