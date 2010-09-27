<?php
/**
 * The HttpUtility provides helper methods for requesting resources via HTTP.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class HttpUtility {

	/**
	 * Send an HTTP POST request, and get a response.
	 * @param  $url: the URL we are posting to.
	 * @param  $data: the data we are posting.
	 * @param  $additionalHeaders: any additional headers we wish to send.
	 * @return the response to the POST.
	 */
	public static function post($url, $data, $additionalHeaders = null) {
		$url = parse_url($url);
		$host = $url['host'];
		$port = isset($url['port']) ? $url['port'] : 80;
		$path = $url['path'];

		$pairs = array();
		foreach ($data as $key => $value) {
			$pairs[] = $key . '=' . urlencode($value);
		}
		$data = join('&', $pairs);

		// Compose HTTP request header
		$headers =
			"POST $path  HTTP/1.0\r\n"
			. "Host: $host\r\n"
			. "User-Agent: PHP\r\n"
			. "Content-Type: application/x-www-form-urlencoded\r\n"
			. "Content-Length: " . strlen($data) . "\r\n"
			. "Connection: close\r\n\r\n";

		$response = '';
		$socket = @pfsockopen($host, $port);

		if ($socket) {
			fputs($socket, $headers . $data);
			while (!feof($socket)) {
				$response .= fgets($socket);
			}
			fclose($socket);
		}
		return $response;
	}

}
