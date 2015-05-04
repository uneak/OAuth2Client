<?php

	namespace Uneak\OAuth2\Token;

	use Uneak\OAuth2\Token\Token;
	use Uneak\OAuth2\Curl\CurlRequest;
	use Uneak\OAuth2\GrantType\GrantTypeInterface;
	use Uneak\OAuth2\GrantType\RefreshToken;
	use Uneak\OAuth2\Exception;

	class AccessToken extends Token {

		protected $grant_type;

		public function __construct(GrantTypeInterface $grant_type) {
			parent::__construct();
			$this->grant_type = $grant_type;
		}

		public function getAccessToken() {
			return ($this->hasExpired()) ? null : $this->token;
		}

		public function eraseToken() {
			$this->setToken(null);
			$this->setTokenType(self::ACCESS_TOKEN_URI);
			$this->setScope(null);
			$this->setRefreshToken(null);
			$this->expires_in = null;
			$this->create_at = time();
		}

		public function parseToken($result) {
			$this->setToken($result['access_token']);
			$this->setTokenType($result['token_type']);
			$this->setScope($result['scope']);
			if (isset($result['refresh_token'])) {
				$this->setRefreshToken($result['refresh_token']);
			}
			$this->expires_in = $result['expires_in'];
			$this->create_at = time();
		}

		private function requestToken(GrantTypeInterface $grant_type = null, $extra_parameters = array()) {
			if (!$grant_type) {
				$grant_type = $this->grant_type;
			}
			$grantRequestParam = $grant_type->getRequestParams();

			$request = new CurlRequest();
			return $request
				->setUrl($grant_type->getApplication()->getTokenEndpoint())
				->setParameters($grantRequestParam['parameters'])
				->setHttpHeaders($grantRequestParam['http_headers'])
				->setCurlExtras($extra_parameters)
				->setCertificateFile($grant_type->getApplication()->getCertificateFile())
				->setHttpMethod(CurlRequest::HTTP_METHOD_POST)
				->setFormContentType(CurlRequest::HTTP_FORM_CONTENT_TYPE_APPLICATION)
				->getResponse()
				;
		}

		public function updateToken() {
			if (!$this->token) {
				$request_token = $this->requestToken();

			} else if ($this->hasExpired()) {
				$refreshTokenGrant = new RefreshToken($this->grant_type->getApplication());
				$refreshTokenGrant->setRefreshToken($this->getRefreshToken());
				$request_token = $this->requestToken($refreshTokenGrant);
				$this->eraseToken();
			} else {
				// have valid token
				return;
			}


			$code = $request_token->getCode();
			$result = $request_token->getResult();

			if ($code == 200) {
				$this->parseToken($result);

			} else if ($request_token->getCode() == 400) {
				$this->eraseToken();
				switch ($result['error_description']) {
					case "Refresh token has expired":
						$this->updateToken();
						break;
					case "Code doesn't exist or is invalid for the client":
					case "The authorization code has expired":
					default:
						throw new Exception('Access Token [400:invalid_request] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);
						break;
				}
				throw new Exception('Access Token Error : [400:invalid_request] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);
			} else  if ($request_token->getCode() == 401) {
				$this->eraseToken();
				switch ($result['error_description']) {
					default:
						throw new Exception('Access Token [401:invalid_token] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);
						break;
				}
				throw new Exception('Access Token Error : [401:invalid_token] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);

			} else  if ($request_token->getCode() == 403) {
				$this->eraseToken();
				switch ($result['error_description']) {
					default:
						throw new Exception('Access Token [403:insufficient_scope] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);
						break;
				}
				throw new Exception('Access Token Error : [403:insufficient_scope] : ' . $result['error_description'], Exception::REQUEST_ACCESS_TOKEN_ERROR);
			}
		}

		public function fetch($url, $parameters = array(), $http_method = CurlRequest::HTTP_METHOD_GET, array $http_headers = array(), $form_content_type = CurlRequest::HTTP_FORM_CONTENT_TYPE_MULTIPART) {

			$this->updateToken();

			switch ($this->getTokenType()) {
				case Token::ACCESS_TOKEN_URI:
					if (is_array($parameters)) {
						$parameters['access_token'] = $this->getToken();
					} else {
						throw new InvalidArgumentException(
							'You need to give parameters as array if you want to give the token within the URI.', InvalidArgumentException::REQUIRE_PARAMS_AS_ARRAY
						);
					}
					break;
				case Token::ACCESS_TOKEN_BEARER:
					$http_headers['Authorization'] = 'Bearer ' . $this->getToken();
					break;
				case Token::ACCESS_TOKEN_OAUTH:
					$http_headers['Authorization'] = 'OAuth ' . $this->getToken();
					break;
				case Token::ACCESS_TOKEN_MAC:
					$http_headers['Authorization'] = 'MAC ' . $this->generateMACSignature($this, $url, $parameters, $http_method);
					break;
				default:
					throw new Exception('Unknown access token type.', Exception::INVALID_ACCESS_TOKEN_TYPE);
			}

			$request = new CurlRequest($url, $parameters, $http_method, $http_headers, $form_content_type);
			return $request->getResponse();
		}

		/** 
		 * Generate the MAC signature
		 *
		 * @param string $url Called URL
		 * @param array  $parameters Parameters
		 * @param string $http_method Http Method
		 * @return string
		 */
		private static function generateMACSignature(Token $token, $url, $parameters, $http_method) {
			$timestamp = time();
			$nonce = uniqid();
			$parsed_url = parse_url($url);
			if (!isset($parsed_url['port'])) {
				$parsed_url['port'] = ($parsed_url['scheme'] == 'https') ? 443 : 80;
			}
			if ($http_method == RestRequest::HTTP_METHOD_GET) {
				if (is_array($parameters)) {
					$parsed_url['path'] .= '?' . http_build_query($parameters, null, '&');
				} elseif ($parameters) {
					$parsed_url['path'] .= '?' . $parameters;
				}
			}

			$signature = base64_encode(hash_hmac($token->getTokenAlgorithm(), $timestamp . "\n"
				. $nonce . "\n"
				. $http_method . "\n"
				. $parsed_url['path'] . "\n"
				. $parsed_url['host'] . "\n"
				. $parsed_url['port'] . "\n\n"
				, $token->getTokenSecret(), true));

			return 'id="' . $token->getToken() . '", ts="' . $timestamp . '", nonce="' . $nonce . '", mac="' . $signature . '"';
		}

	}
