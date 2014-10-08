<?php

namespace Uneak\OAuth2\Curl;

class CurlResponse {

	protected $result;
	protected $code;
	protected $content_type;

	public function __construct($code, $content_type, $result) {
		$this->code = $code;
		$this->content_type = $content_type;
		$this->result = $result;
	}

	public function getCode() {
		return $this->code;
	}

	public function getContentType() {
		return $this->content_type;
	}

	public function getResult() {
		return $this->result;
	}

}
