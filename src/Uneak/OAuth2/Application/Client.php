<?php

namespace Uneak\OAuth2\Application;

use Uneak\OAuth2\InvalidArgumentException;
use Uneak\OAuth2\Application\ClientInterface;

class Client implements ClientInterface {

	/**
	 * Different AUTH method
	 */
	const AUTH_TYPE_URI = 0;
	const AUTH_TYPE_AUTHORIZATION_BASIC = 1;
	const AUTH_TYPE_FORM = 2;

	/**
	 * Client ID
	 *
	 * @var string
	 */
	protected $client_id = null;

	/**
	 * Client Secret
	 *
	 * @var string
	 */
	protected $client_secret = null;

	/**
	 * Client Authentication method
	 *
	 * @var int
	 */
	protected $client_auth = self::AUTH_TYPE_URI;

	/**
	 * The path to the certificate file to use for https connections
	 *
	 * @var string  Defaults to .
	 */
	protected $certificate_file = null;

	/**
	 * Construct
	 *
	 * @param string $client_id Client ID
	 * @param string $client_secret Client Secret
	 * @param int    $client_auth (AUTH_TYPE_URI, AUTH_TYPE_AUTHORIZATION_BASIC, AUTH_TYPE_FORM)
	 * @param string $certificate_file Indicates if we want to use a certificate file to trust the server. Optional, defaults to null.
	 * @return void
	 */
	public function __construct($client_id, $client_secret, $client_auth = self::AUTH_TYPE_URI, $certificate_file = null) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->client_auth = $client_auth;
		$this->setCertificateFile($certificate_file);
	}

	/**
	 * Get the client Id
	 *
	 * @return string Client ID
	 */
	public function getClientId() {
		return $this->client_id;
	}

	/**
	 * Set the Client Id
	 *
	 * @param string $certificate_file
	 * @return void
	 */
	public function setClientId($client_id) {
		$this->client_id = $client_id;
		return $this;
	}

	/**
	 * Get the client Secret
	 *
	 * @return string Client Secret
	 */
	public function getClientSecret() {
		return $this->client_secret;
	}

	/**
	 * Set the client Secret
	 *
	 * @param string $client_secret
	 * @return void
	 */
	public function setClientSecret($client_secret) {
		$this->client_secret = $client_secret;
		return $this;
	}

	/**
	 * Set the client authentication type
	 *
	 * @param string $client_auth (AUTH_TYPE_URI, AUTH_TYPE_AUTHORIZATION_BASIC, AUTH_TYPE_FORM)
	 * @return void
	 */
	public function setClientAuthType($client_auth) {
		$this->client_auth = $client_auth;
		return $this;
	}

	/**
	 * Get the ClientAuthType
	 *
	 * @return string ClientAuthType
	 */
	public function getClientAuthType() {
		return $this->client_auth;
	}

	/**
	 * Set the Certificate File
	 *
	 * @param string $certificate_file
	 * @return void
	 */
	public function setCertificateFile($certificate_file) {
		if (!empty($certificate_file) && !is_file($certificate_file)) {
			throw new InvalidArgumentException('The certificate file was not found', InvalidArgumentException::CERTIFICATE_NOT_FOUND);
		}
		$this->certificate_file = $certificate_file;
		return $this;
	}

	/**
	 * Get the Certificate File
	 *
	 * @return string Certificate File
	 */
	public function getCertificateFile() {
		return $this->certificate_file;
	}

}
