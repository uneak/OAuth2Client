<?php

namespace Uneak\OAuth2\Application;

interface ClientInterface {

	public function getClientId();
	public function setClientId($client_id);
	public function getClientSecret();
	public function setClientSecret($client_secret);
	public function setClientAuthType($client_auth);
	public function getClientAuthType();
	public function setCertificateFile($certificate_file);
	public function getCertificateFile();
}
