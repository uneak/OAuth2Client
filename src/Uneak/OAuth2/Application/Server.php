<?php

namespace Uneak\OAuth2\Application;
use Uneak\OAuth2\Application\ServerInterface;

class Server implements ServerInterface {

	protected $authEndpoint;
	protected $tokenEndpoint;
	protected $redirectUrl;

	public function __construct($authEndpoint, $tokenEndpoint, $redirectUrl) {
		$this->authEndpoint = $authEndpoint;
		$this->tokenEndpoint = $tokenEndpoint;
		$this->redirectUrl = $redirectUrl;
	}

	public function setAuthEndpoint($authEndpoint) {
		$this->authEndpoint = $authEndpoint;
		return $this;
	}

	public function getAuthEndpoint() {
		return $this->authEndpoint;
	}

	public function setTokenEndpoint($tokenEndpoint) {
		$this->tokenEndpoint = $tokenEndpoint;
		return $this;
	}

	public function getTokenEndpoint() {
		return $this->tokenEndpoint;
	}

	public function setRedirectUrl($redirectUrl) {
		$this->redirectUrl = $redirectUrl;
		return $this;
	}

	public function getRedirectUrl() {
		return $this->redirectUrl;
	}


}
