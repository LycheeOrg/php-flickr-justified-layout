<?php

namespace LycheeOrg\PhpFlickrJustifiedLayout\Exceptions;

class JustifiedException extends \Exception
{
	public function __construct(string $message)
	{
		parent::__construct($message, 0, null);
	}
}