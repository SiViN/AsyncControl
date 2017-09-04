<?php

namespace Pd\AsyncControl\UI;

final class AsyncControlLink
{

	private static $defaultMessage = 'Load content';
	private static $defaultAttributes = [];
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var array
	 */
	private $attributes;


    /**
     * AsyncControlLink constructor.
     * @param string|NULL $message
     * @param array|NULL $attributes
     */
    public function __construct(
		$message = NULL,
		array $attributes = NULL
	) {
		$this->message = $message === NULL ? self::$defaultMessage : $message;
		$this->attributes = $attributes === NULL ? self::$defaultAttributes : $attributes;
	}


    /**
     * @param $message
     * @param array $attributes
     */
    public static function setDefault($message, array $attributes = [])
	{
		self::$defaultMessage = $message;
		self::$defaultAttributes = $attributes;
	}


    /**
     * @return string|NULL
     */
    public function getMessage()
	{
		return $this->message;
	}


    /**
     * @return array
     */
    public function getAttributes()
	{
		return $this->attributes;
	}
}
