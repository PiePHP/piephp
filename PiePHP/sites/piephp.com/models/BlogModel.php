<?php

class BlogModel extends Model {

	public $cacheConfigName = '';

	public function posts() {
		return $this->results('SELECT * FROM posts', 60);
	}

}

?>