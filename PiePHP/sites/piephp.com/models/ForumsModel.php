<?php

class ForumsModel extends Model {

	public function forums() {
		$forumScaffold = new ForumScaffold();
		return $this->results('SELECT id, name FROM forums LIMIT 0, 10');
	}

}