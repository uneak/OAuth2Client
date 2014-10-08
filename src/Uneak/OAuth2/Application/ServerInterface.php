<?php

namespace Uneak\OAuth2\Application;

interface ServerInterface {

	public function setAuthEndpoint($authEndpoint);
	public function getAuthEndpoint();
	public function setTokenEndpoint($tokenEndpoint);
	public function getTokenEndpoint();
	public function setRedirectUrl($redirectUrl);
	public function getRedirectUrl();
}
