<?php

namespace Uneak\OAuth2\Curl;

use Uneak\OAuth2\Curl\CurlResponse;

class CurlRequest {

	const HTTP_METHOD_GET = 'GET';
	const HTTP_METHOD_POST = 'POST';
	const HTTP_METHOD_PUT = 'PUT';
	const HTTP_METHOD_DELETE = 'DELETE';
	const HTTP_METHOD_HEAD = 'HEAD';
	const HTTP_METHOD_PATCH = 'PATCH';
	//
	const HTTP_FORM_CONTENT_TYPE_APPLICATION = 0;
	const HTTP_FORM_CONTENT_TYPE_MULTIPART = 1;

	protected $url;
	protected $http_headers;
	protected $http_method;
	protected $parameters;
	protected $form_content_type;
	protected $curl_extras;
	protected $certificate_file;

	public function __construct($url = "", array $parameters = array(), $http_method = self::HTTP_METHOD_GET, array $http_header = array(), $form_content_type = self::HTTP_FORM_CONTENT_TYPE_MULTIPART, array $curl_extras = array(), $certificate_file = null) {
		$this->url = $url;
		$this->http_headers = $http_header;
		$this->http_method = $http_method;
		$this->parameters = $parameters;
		$this->form_content_type = $form_content_type;
		$this->curl_extras = $curl_extras;
		$this->certificate_file = $certificate_file;
	}

	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}

	public function getUrl() {
		return $this->url;
	}

	
	public function setCertificateFile($certificate_file) {
		$this->certificate_file = $certificate_file;
		return $this;
	}

	public function getCertificateFile() {
		return $this->certificate_file;
	}
	
	
	public function setHttpMethod($http_method) {
		$this->http_method = $http_method;
		return $this;
	}

	public function getHttpMethod() {
		return $this->http_method;
	}
	
	
	
	public function setFormContentType($form_content_type) {
		$this->form_content_type = $form_content_type;
		return $this;
	}

	public function getFormContentType() {
		return $this->form_content_type;
	}
	
	
	
	
	

	public function addParameter($key, $value) {
		$this->parameters[$key] = $value;
		return $this;
	}

	public function removeParameter($key) {
		unset($this->parameters[$key]);
		return $this;
	}

	public function setParameters(array $parameters) {
		$this->parameters = array_merge($this->parameters, $parameters);
		return $this;
	}

	public function getParameters() {
		return $this->parameters;
	}

	


	

	public function addHttpHeader($key, $value) {
		$this->http_headers[$key] = $value;
		return $this;
	}

	public function removeHttpHeader($key) {
		unset($this->http_headers[$key]);
		return $this;
	}

	public function setHttpHeaders(array $parameters) {
		$this->http_headers = array_merge($this->http_headers, $parameters);
		return $this;
	}

	public function getHttpHeaders() {
		return $this->http_headers;
	}

	

	
	
	
	public function addCurlExtra($key, $value) {
		$this->curl_extras[$key] = $value;
		return $this;
	}

	public function removeCurlExtra($key) {
		unset($this->curl_extras[$key]);
		return $this;
	}

	public function setCurlExtras(array $curl_extras) {
		$this->curl_extras = array_merge($this->curl_extras, $curl_extras);
		return $this;
	}

	public function getCurlExtras() {
		return $this->curl_extras;
	}	
	
	

	public function getResponse() {

		$curl_options = array();
		$curl_options[CURLOPT_RETURNTRANSFER] = true;
		$curl_options[CURLOPT_SSL_VERIFYPEER] = true;
		$curl_options[CURLOPT_CUSTOMREQUEST] = $this->http_method;

		switch ($this->http_method) {
			case self::HTTP_METHOD_POST:
				$curl_options[CURLOPT_POST] = true;
			case self::HTTP_METHOD_PUT:
			case self::HTTP_METHOD_PATCH:
				if (is_array($this->parameters) && self::HTTP_FORM_CONTENT_TYPE_APPLICATION === $this->form_content_type) {
					$this->parameters = http_build_query($this->parameters, null, '&');
				}
				$curl_options[CURLOPT_POSTFIELDS] = $this->parameters;
				break;
			case self::HTTP_METHOD_HEAD:
				$curl_options[CURLOPT_NOBODY] = true;
			case self::HTTP_METHOD_DELETE:
			case self::HTTP_METHOD_GET:
				if (is_array($this->parameters)) {
					$this->url .= '?' . http_build_query($this->parameters, null, '&');
				} elseif ($this->parameters) {
					$this->url .= '?' . $this->parameters;
				}
				break;
			default:
				break;
		}

		$curl_options[CURLOPT_URL] = $this->url;

		if (is_array($this->http_headers)) {
			$header = array();
			foreach ($this->http_headers as $key => $parsed_urlvalue) {
				$header[] = "$key: $parsed_urlvalue";
			}
			$curl_options[CURLOPT_HTTPHEADER] = $header;
		}

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		// https handling
		if (!empty($this->certificate_file)) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_CAINFO, $this->certificate_file);
		} else {
			// bypass ssl verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		if (!empty($this->curl_extras)) {
			curl_setopt_array($ch, $this->curl_extras);
		}

		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

		$curl_error = curl_error($ch);
		if ($curl_error) {
			throw new Exception($curl_error, Exception::CURL_ERROR);
		} else {
			$json_decode = json_decode($result, true);
		}
		curl_close($ch);

		return new CurlResponse($http_code, $content_type, (null === $json_decode) ? $result : $json_decode);
	}

}
