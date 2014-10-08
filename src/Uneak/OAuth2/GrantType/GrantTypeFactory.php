<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\Application\Application;
use Uneak\OAuth2\Exception;
use Uneak\OAuth2\InvalidArgumentException;


class GrantTypeFactory {

	protected $application;

	public function __construct(Application $application) {
		$this->application = $application;
	}

	public function get($grant_type) {
		if (!$grant_type) {
			throw new InvalidArgumentException('The grant_type is mandatory.', InvalidArgumentException::INVALID_GRANT_TYPE);
		}
		$grantTypeClassName = $this->convertToCamelCase($grant_type);
		$grantTypeClass = __NAMESPACE__. "\\".$grantTypeClassName;
		
		if (!class_exists($grantTypeClass)) {
			throw new InvalidArgumentException('Unknown grant type \'' . $grant_type . '\'', InvalidArgumentException::INVALID_GRANT_TYPE);
		}
		$grantTypeObject = new $grantTypeClass($this->application);

		if (!defined($grantTypeClass . '::GRANT_TYPE')) {
			throw new Exception('Unknown constant GRANT_TYPE for class ' . $grantTypeClassName, Exception::GRANT_TYPE_ERROR);
		}
		
		return $grantTypeObject;
	}

	
	private function convertToCamelCase($grant_type) {
		$parts = explode('_', $grant_type);
		array_walk($parts, function(&$item) {
			$item = ucfirst($item);
		});
		return implode('', $parts);
	}

}
