<?php
namespace App\Exceptions;

use RuntimeException;

class ServiceValidationException extends RuntimeException
{

	private $field;

	public function __construct($message, $field = 'general')
	{
		parent::__construct($message);

		$this->field = $field;
	}

	public function toArray()
	{
		return [$this->field => [$this->getMessage()]];
	}

}
