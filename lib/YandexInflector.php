<?php
namespace mmjurov;
use mmjurov\YandexInflectorBitrixCache as YandexInflectorBitrixCache;

/**
 * Class YandexInflector
 * Класс для склонения слов с помощью сервиса Яндекса
 */
class YandexInflector
{
	const BASE_URI = 'http://export.yandex.ru/';
	const TIMEOUT = 3;

	private $baseWord = '';
	private $inflections = array();
	private $obCache;
	private $useCache = false;

	function __construct( $cacheObject = 'mmjurov\\YandexInflectorBitrixCache', $cacheOptions = array() )
	{
		if (class_exists($cacheObject) && get_parent_class($cacheObject) === 'mmjurov\\YandexInflectorCache')
		{
			$this->obCache = new $cacheObject( $cacheOptions );
			$this->useCache = true;
		}
		else
		{
			$this->useCache = false;
		}
	}

	protected function prepareWord( &$word )
	{
		trim($word);
		return strlen($word);
	}

	protected function getInflectionPath()
	{
		return 'inflect.xml';
	}

	private function get()
	{
		$cacheVars = false;
		if ($this->useCache && $this->obCache->connect())
		{
			$cacheVars = $this->obCache->get( $this->baseWord );
			$this->inflections = $cacheVars;
		}
		else
		{
			$this->useCache = false;
		}

		if ($cacheVars === false || !$this->useCache)
		{
			$url = self::BASE_URI
				. $this->getInflectionPath()
				. '?'
				. http_build_query( array('name' => $this->baseWord) );

			$context = stream_context_create(array(
				'http' => array('timeout' => self::TIMEOUT)
			));
			$xmlResponse = file_get_contents( $url, false, $context );

			if ($this->parseResponse($xmlResponse) && $this->useCache)
			{
				$this->obCache->set( $this->baseWord, $this->inflections );
			}

		}

	}

	private function parseResponse($xml)
	{
		try
		{
			$obXml = new \SimpleXmlElement( $xml );
		}
		catch( \Exception $e )
		{
			throw new YandexInflectorException('INVALID_RESPONSE_FROM_SERVICE');
		}

		if (!property_exists($obXml, 'inflection'))
		{
			throw new YandexInflectorException('UNEXPECTED_RESPONSE_FROM_SERVICE');
		}

		foreach ($obXml->inflection as $obInflection )
		{
			$this->inflections[] = (string)$obInflection;
		}

		return true;

	}

	public function inflect($word)
	{
		$isPrepared = $this->prepareWord($word);

		if ( $isPrepared )
		{
			$this->baseWord = $word;
			$this->get();
			return $this->getInflections();
		}
		else
		{
			throw new YandexInflectorException('WORD_NOT_VALID');
		}
	}

	public function getOriginal()
	{
		return $this->baseWord;
	}

	public function getNominative()
	{
		return $this->getInflection(0);
	}

	public function getGenitive()
	{
		return $this->getInflection(1);
	}

	public function getDative()
	{
		return $this->getInflection(2);
	}

	public function getAccusative()
	{
		return $this->getInflection(3);
	}

	public function getInstrumental()
	{
		return $this->getInflection(4);
	}

	public function getPrepositional()
	{
		return $this->getInflection(5);
	}

	public function getInflections()
	{
		return $this->inflections;
	}

	public function getInflection( $code )
	{
		$code = strtolower($code);
		switch ($code)
		{
			case 'nominative':
			case 'именительный':
			case 0:
				$inflectionNum = 0;
				break;

			case 'genitive':
			case 'родительный':
			case 1:
				$inflectionNum = 1;
				break;

			case 'dative':
			case 'дательный':
			case 2:
				$inflectionNum = 2;
				break;

			case 'accusative':
			case 'винительный':
			case 3:
				$inflectionNum = 3;
				break;

			case 'instrumental':
			case 'творительный':
			case 4:
				$inflectionNum = 4;
				break;

			case 'prepositional':
			case 'предложный':
			case 5:
				$inflectionNum = 5;
				break;

			default:
				$inflectionNum = 0;
				break;
		}

		return (!empty($this->inflections) && strlen($this->inflections[ $inflectionNum ]) > 0) ?
			$this->inflections[ $inflectionNum ] :
			$this->baseWord;
	}
}

/**
 * Исключение для класса склонятора
 */
class YandexInflectorException extends \Exception {};