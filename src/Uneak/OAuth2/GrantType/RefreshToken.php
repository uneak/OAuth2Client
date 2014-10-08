<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\GrantType\AbstractGrantType;
use Uneak\OAuth2\Application\Application;

class RefreshToken extends AbstractGrantType {

	const GRANT_TYPE = 'refresh_token';
	protected $refresh_token;
	
	public function __construct(Application $application) {
		parent::__construct($application);
	}

	public function setRefreshToken($refresh_token) {
		$this->refresh_token = $refresh_token;
		return $this;
	}

	public function getRefreshToken() {
		return $this->refresh_token;
	}
	
	public function getRequestParams() {
		$requestParams = parent::getRequestParams();
		$requestParams['parameters'] = array_merge($requestParams['parameters'], array(
			'grant_type' => self::GRANT_TYPE,
			'refresh_token' => $this->getRefreshToken(),
		));
		return $requestParams;
	}

}
