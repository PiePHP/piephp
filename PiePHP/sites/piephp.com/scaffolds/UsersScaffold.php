<?php

class UsersScaffold extends Scaffold {

	var $fields = array(
		'name' => array(
			'type' => 'FirstAndLastName',
			'length' => 255,
			'required' => true
		),
		'email' => array(
			'type' => 'Email',
			'length' => 255,
			'required' => true
		),
		'username' => array(
			'type' => 'Username',
			'length' => 255,
			'required' => true
		),
		'password' => array(
			'type' => 'Password',
			'length' => 255,
			'required' => true
		)
	);

}