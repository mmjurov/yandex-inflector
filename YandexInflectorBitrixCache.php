<?php

class YandexInflectorBitrixCache extends YandexInflectorCache
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
		$this->obCache = new CPHPCache;
	}

	function get($key)
	{
		if ($this->obCache->InitCache($this->cacheTime, $key, self::CACHE_DIR))
		{
			return $this->obCache->GeVars();
		}
		return false;
	}

	function set($key, $value)
	{
		if ($this->obCache->InitCache($this->cacheTime, $key, self::CACHE_DIR))
		{
			if ($this->obCache->StartDataCache())
			{
				$this->EndDataCache($value);
				return true;
			}
		}
		return false;
	}
}