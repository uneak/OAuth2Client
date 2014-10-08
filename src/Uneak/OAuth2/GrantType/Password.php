<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\GrantType\AbstractGrantType;
use Uneak\OAuth2\Application\Application;

class Password extends AbstractGrantType {

	const GRANT_TYPE = 'password';

	protected $username;
	protected $password;

	public function __construct(Application $application) {
		parent::__construct($application);
	}

	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	public function getRequestParams() {
		$requestParams = parent::getRequestParams();
		$requestParams['parameters'] = array_merge($requestParams['parameters'], array(
			'grant_type' => self::GRANT_TYPE,
			'username' => $this->username,
			'password' => $this->password,
		));
		return $requestParams;
	}

}
