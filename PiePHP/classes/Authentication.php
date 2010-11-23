<?php
/**
 * Authentication helper class
 *
 * @package    PiePHP
 * @since      Version 0.0.4
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class Authentication {

	/**
	 * Produce the one-way hash of a plain text password
	 * @param string $password The password in plain text
	 * @param string $salt     An optional salt
	 * @return string A one-way hash
	 */
	public static function hashPasswordSalt($password, $salt = '') {
		return md5($password . $salt);
	}

	/**
	 * Generate a random new password
	 * @param int $length Optionally specify the desired length of the password
	 * @return string A new password
	 */
	public static function generatePassword($length = 10)
	{
		$sample = base64_encode(md5(mt_rand()));
		return substr($sample, 0, $length);
	}
}
