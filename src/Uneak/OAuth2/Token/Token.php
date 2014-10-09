<?php

namespace Uneak\OAuth2\Token;

class Token {

	const ACCESS_TOKEN_URI = "uri";
	const ACCESS_TOKEN_BEARER = "bearer";
	const ACCESS_TOKEN_OAUTH = "oauth";
	const ACCESS_TOKEN_MAC = "mac";

	protected $token = null;
	protected $token_type = self::ACCESS_TOKEN_URI;
	protected $token_secret = null;
	protected $token_algorithm = null;
	protected $expires_in = null;
	protected $scope = null;
	protected $refresh_token = null;
	protected $create_at = null;

	public function __construct() {
		$this->expires_in = 0;
		$this->create_at = time();
	}
	
	public function getRefreshToken() {
		return $this->refresh_token;
	}

	public function setRefreshToken($refresh_token) {
		$this->refresh_token = $refresh_token;
		return $this;
	}

	public function hasExpired() {
		return ($this->getExpiresIn() < 60); // indique qu'il est expiré une minute avant le vrai moment
	}

	public function getExpiresIn() {
		return ($this->create_at + $this->expires_in) - time();
	}

	public function getExpiresAt() {
		return $this->create_at + $this->expires_in;
	}

	public function getScope() {
		return $this->scope;
	}

	public function setScope($scope) {
		$this->scope = $scope;
		return $this;
	}

	public function getToken() {
		return $this->token;
	}

	public function setToken($token) {
		$this->token = $token;
		return $this;
	}

	public function getTokenType() {
		return $this->token_type;
	}

	public function setTokenType($access_token_type) {
		$this->token_type = $access_token_type;
		return $this;
	}

	public function getTokenSecret() {
		return $this->token_secret;
	}

	public function setTokenSecret($access_token_secret) {
		$this->token_secret = $access_token_secret;
		return $this;
	}

	public function getTokenAlgorithm() {
		return $this->token_algorithm;
	}

	public function setTokenAlgorithm($access_token_algorithm) {
		$this->token_algorithm = $access_token_algorithm;
		return $this;
	}

}