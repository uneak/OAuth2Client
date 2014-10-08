<?php

namespace Uneak\OAuth2;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException {

	const INVALID_GRANT_TYPE = 0x01;
	const CERTIFICATE_NOT_FOUND = 0x02;
	const REQUIRE_PARAMS_AS_ARRAY = 0x03;
	const MISSING_PARAMETER = 0x04;

}
