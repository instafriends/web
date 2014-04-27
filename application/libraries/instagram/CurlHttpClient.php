<?php
class CurlHttpClient {
	const DEFAULT_USER_AGENT = 'PHPAPI';
	const GET = 'GET';
	const POST = 'POST';
	const DELETE = 'DELETE';
	private $handler;
	private $method = '';
	private $postParams = array();
	public function __construct($uri) {
		$this->handler = curl_init($uri);
		$this->_setOptions();
	}
	protected function _setOptions() {
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_USERAGENT, self::DEFAULT_USER_AGENT);
	}
	public function setUri($uri) {
		$this->handler = curl_init($uri);
		$this->_setOptions();
	}
	public function setHeaders($value = true) {
		curl_setopt($this->handler, CURLOPT_HEADER, $value);
	}
	public function setMethod($method = self::GET) {
		$this->method = self::GET;
		switch ($method) {
			case self::GET :
				curl_setopt($this->handler, CURLOPT_HTTPGET, true);
			break;
			case self::POST :
				curl_setopt($this->handler, CURLOPT_POST, true);
			break;
			case self::DELETE :
				curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, self::DELETE);
			break;
			default:
				throw new CurlHttpClientException('Method not supported');
		}
	}
	public function setPostParam($name, $value) {
		$this->postParams[$name] = $value;
		curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->postParams);
	}
	public function getResponse() {
		$response = curl_exec($this->handler);
		curl_close($this->handler);
		return $response;
	}
	protected function extractHeaders($response) {
		$headers = array();
		$parts = preg_split('|(?:\r?\n){2}|m', $response_str, 2);
		if (!$parts[0]) return $headers;
		$lines = explode("\n", $parts[0]);
		unset($parts);
		$last_header = null;
		foreach($lines as $line) {
			$line = trim($line, "\r\n");
			if ($line == "") break;
				if (preg_match("|^([\w-]+):\s*(.+)|", $line, $m)) {
					unset($last_header);
					$h_name = strtolower($m[1]);
					$h_value = $m[2];
					if (isset($headers[$h_name])) {
						if (! is_array($headers[$h_name])) {
							$headers[$h_name] = array($headers[$h_name]);
						}
						$headers[$h_name][] = $h_value;
					} else {
						$headers[$h_name] = $h_value;
					}
					$last_header = $h_name;
				} else if (preg_match("|^\s+(.+)$|", $line, $m) && $last_header !== null) {
					if (is_array($headers[$last_header])) {
						end($headers[$last_header]);
						$last_header_key = key($headers[$last_header]);
						$headers[$last_header][$last_header_key] .= $m[1];
					} else {
						$headers[$last_header] .= $m[1];
					}
				}
		}
		return $headers;
	}
}
class CurlHttpClientException extends Exception {}