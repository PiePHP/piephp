<?php

class ForumsScaffold extends Scaffold {

	var $fields = array(
		'forumDescriptiveName' => array(
			'type' => 'Name',
			'length' => 255,
			'required' => true
		),
		'forumPostStuff' => array(
			'type' => 'Name',
			'length' => 255,
			'required' => true
		),
	);

	function forums() {
		$forumScaffold = new ForumScaffold();
		return $this->results('SELECT id, name FROM forums LIMIT 0, 10');
	}

}