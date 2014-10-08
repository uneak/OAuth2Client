<?php

namespace Uneak\OAuth2\GrantType;

use Uneak\OAuth2\GrantType\AbstractGrantType;
use Uneak\OAuth2\Application\Application;

class ClientCredentials extends AbstractGrantType {

	const GRANT_TYPE = 'client_credentials';

	public function __construct(Application $application) {
		parent::__construct($application);
	}

	public function getRequestParams() {
		$requestParams = parent::getRequestParams();
		$requestParams['parameters'] = array_merge($requestParams['parameters'], array(
			'grant_type' => self::GRANT_TYPE,
		));
		return $requestParams;
	}

}
