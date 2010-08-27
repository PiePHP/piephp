<?php

class BlogModel extends Model {

	public $cacheConfigName = '';

	function posts() {
		return $this->results('SELECT * FROM posts', 60);
	}

}

?>