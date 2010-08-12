<?php

class BlogModel extends Model {

	public $cacheConfigKey = '';

	function posts() {
		return $this->select('* FROM posts', 60);
	}

}

?>