<?php

namespace Uneak\OAuth2;

use Exception as BaseException;

class Exception extends BaseException {

	const CURL_NOT_FOUND = 0x01;
	const CURL_ERROR = 0x02;
	const GRANT_TYPE_ERROR = 0x03;
	const INVALID_CLIENT_AUTHENTICATION_TYPE = 0x04;
	const INVALID_ACCESS_TOKEN_TYPE = 0x05;
        const REQUEST_ACCESS_TOKEN_ERROR = 0x06;

}
