<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\GrantType\GrantTypeInterface;
use Uneak\OAuth2\Application\Application;
use Uneak\OAuth2\Application\Client;
use Uneak\OAuth2\Token\AccessToken;
use Uneak\OAuth2\Exception;

abstract class AbstractGrantType implements GrantTypeInterface {

	protected $application;
	protected $access_token = null;

	public function __construct(Application $application) {
		$this->application = $application;
	}

	public function access() {
		if (!$this->access_token) {
			$this->access_token = new AccessToken($this);
		}
		return $this->access_token;
	}

	public function getApplication() {
		return $this->application;
	}

	public function getRequestParams() {
		$parameters = array();
		$http_headers = array();
		
		switch ($this->getApplication()->getClientAuthType()) {
			case Client::AUTH_TYPE_URI:
			case Client::AUTH_TYPE_FORM:
				$parameters['client_id'] = $this->getApplication()->getClientId();
				$parameters['client_secret'] = $this->getApplication()->getClientSecret();
				break;
			case Client::AUTH_TYPE_AUTHORIZATION_BASIC:
				$parameters['client_id'] = $this->getApplication()->getClientId();
				$http_headers['Authorization'] = 'Basic ' . base64_encode($this->getApplication()->getClientId() . ':' . $this->getApplication()->getClientSecret());
				break;
			default:
				throw new Exception('Unknown client auth type.', Exception::INVALID_CLIENT_AUTHENTICATION_TYPE);
		}

		return array(
			'parameters' => $parameters,
			'http_headers' => $http_headers,
		);
	}

}
