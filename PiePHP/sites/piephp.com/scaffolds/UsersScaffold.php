<?php

class UsersScaffold extends Scaffold {

	public $fields = array(
		'name' => array(
			'type' => 'FirstAndLastName',
			'required' => true
		),
		'email' => array(
			'type' => 'Email',
			'required' => true
		),
		'username' => array(
			'type' => 'Username',
			'required' => true
		),
		'password' => array(
			'type' => 'Password',
			'required' => true
		)
	);

	public $fieldsets = array(
		'Personal information' => array('name', 'email'),
		'Authentication' => array('username', 'password'),
	);

	public $listFields = array('username', 'name', 'email');

}