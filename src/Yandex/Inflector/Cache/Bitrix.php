<?php
namespace Yandex\Inflector\Cache;
use Yandex\Inflector\Cache\Provider;

/**
 * Class Bitrix
 * @package mmjurov\Yandex\Inflector\Cache
 * Класс, который является реализацией кеширования под битрикс для склонятора
 */
class Bitrix extends \Yandex\Inflector\Cache\Provider
{
	private $cacheTime = 3600000;
	private $obCache;
	const CACHE_DIR = '/yandex_inflector/';

	function __construct($options)
	{
		if (isset($options['cacheTime']) && $options['cacheTime'] >= 0)
		{
			$this->cacheTime = $options['cacheTime'];
		}
	}

	function connect()
	{
		if (class_exists('\\CPHPCache'))
		{
			$this->obCache = new \CPHPCache;
		}
		return is_object($this->obCache);
	}

	function get($key)
	{
		if ($this->obCache->InitCache($this->cacheTime, $key, self::CACHE_DIR))
		{
			return $this->obCache->GetVars();
		}
		return false;
	}

	function set($key, $value)
	{
		if (!$this->obCache->InitCache($this->cacheTime, $key, self::CACHE_DIR))
		{
			if ($this->obCache->StartDataCache())
			{
				$this->obCache->EndDataCache($value);
				return true;
			}
		}
		return false;
	}
}