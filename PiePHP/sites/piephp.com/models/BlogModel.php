<?php

class BlogModel extends Model {

	var $cacheConfigKey = '';

	function posts() {
		return $this->select('* FROM posts', 60);
	}

}

?>