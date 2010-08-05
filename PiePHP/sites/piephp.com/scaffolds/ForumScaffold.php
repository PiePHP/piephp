<?php

class ForumsScaffold extends Scaffold {

	var $fields = array(
		'name' => array(
			'type' => 'Name',
			'length' => 255,
			'required' => true
		),
	);

}