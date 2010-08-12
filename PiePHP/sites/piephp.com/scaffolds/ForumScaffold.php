<?php

class ForumsScaffold extends Scaffold {

	public $fields = array(
		'name' => array(
			'type' => 'Name',
			'length' => 255,
			'required' => true
		),
	);

}