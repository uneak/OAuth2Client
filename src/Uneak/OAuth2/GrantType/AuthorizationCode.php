<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\GrantType\AbstractGrantType;
use Uneak\OAuth2\Application\Application;

class AuthorizationCode extends AbstractGrantType {

	const GRANT_TYPE = 'authorization_code';

	protected $code;

	public function __construct(Application $application) {
		parent::__construct($application);
	}

	public function setCode($code) {
		$this->code = $code;
		return $this;
	}

	public function getCode() {
		return $this->code;
	}

	public function getAuthenticationUrl($scope = null, $state = null) {
		$extra_parameters = array();

		if ($scope) {
			$extra_parameters['scope'] = $scope;
		}
		if ($state) {
			$extra_parameters['state'] = $state;
		}

		$parameters = array_merge(array(
			'response_type' => 'code',
			'client_id' => $this->getApplication()->getClientId(),
			'redirect_uri' => $this->getApplication()->getRedirectUrl())
				, $extra_parameters
		);
		return $this->getApplication()->getAuthEndpoint() . '?' . http_build_query($parameters, null, '&');
	}

	public function getRequestParams() {
		$requestParams = parent::getRequestParams();
		$requestParams['parameters'] = array_merge($requestParams['parameters'], array(
			'grant_type' => self::GRANT_TYPE,
			'redirect_uri' => $this->getApplication()->getRedirectUrl(),
			'code' => $this->getCode(),
		));
		return $requestParams;
	}

}
