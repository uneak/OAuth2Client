<?php

namespace Uneak\OAuth2\GrantType;

interface GrantTypeInterface {
	public function getRequestParams();
	public function getApplication();
	public function access();
}
