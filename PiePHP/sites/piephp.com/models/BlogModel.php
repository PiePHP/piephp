<?php
/**
 * A dummy blog model for testing performance.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class BlogModel extends Model {

	/**
	 * Turn the cache off.
	 * TODO: Do this in a more straightforward way (probably with a boolean).
	 */
	public $cacheConfigName = '';

	/**
	 * Get the existing blog posts from the database.
	 * @return the posts as an array of associative arrays.
	 */
	public function posts() {
		return $this->results('SELECT * FROM posts', 60);
	}

}

?>