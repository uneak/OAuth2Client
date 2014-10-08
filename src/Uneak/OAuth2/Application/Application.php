<?php

namespace Uneak\OAuth2\Application;

use Uneak\OAuth2\Application\Client;
use Uneak\OAuth2\Application\ClientInterface;
use Uneak\OAuth2\Application\Server;
use Uneak\OAuth2\Application\ServerInterface;
use Uneak\OAuth2\GrantType\GrantTypeFactory;

class Application implements ClientInterface, ServerInterface {

	protected $client;
	protected $server;
	protected $grant_type_factory;

	public function __construct(Client $client, Server $server) {
		$this->client = $client;
		$this->server = $server;
		$this->grant_type_factory = new GrantTypeFactory($this);
	}

	public function getGrantType($grant_type) {
		return $this->grant_type_factory->get($grant_type);
	}
	
	public function setServer($server) {
		$this->server = $server;
	}

	public function setClient($client) {
		$this->client = $client;
	}

	//
	//
	//
	public function getCertificateFile() {
		return $this->client->getCertificateFile();
	}

	public function getClientAuthType() {
		return $this->client->getClientAuthType();
	}

	public function getClientId() {
		return $this->client->getClientId();
	}

	public function getClientSecret() {
		return $this->client->getClientSecret();
	}

	public function setCertificateFile($certificate_file) {
		$this->client->setCertificateFile($certificate_file);
                return $this;
	}

	public function setClientAuthType($client_auth) {
		$this->client->setClientAuthType($client_auth);
                return $this;
	}

	public function setClientId($client_id) {
		$this->client->setClientId($client_id);
                return $this;
	}

	public function setClientSecret($client_secret) {
		$this->client->setClientSecret($client_secret);
                return $this;
	}

	//
	//
	//
	public function getAuthEndpoint() {
		return $this->server->getAuthEndpoint();
	}

	public function getRedirectUrl() {
		return $this->server->getRedirectUrl();
	}

	public function getTokenEndpoint() {
		return $this->server->getTokenEndpoint();
	}

	public function setAuthEndpoint($authEndpoint) {
		$this->server->setAuthEndpoint($authEndpoint);
                return $this;
	}

	public function setRedirectUrl($redirectUrl) {
		$this->server->setRedirectUrl($redirectUrl);
                return $this;
	}

	public function setTokenEndpoint($tokenEndpoint) {
		$this->server->setTokenEndpoint($tokenEndpoint);
                return $this;
	}

}
