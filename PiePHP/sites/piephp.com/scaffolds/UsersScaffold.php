<?php

class UsersScaffold extends Scaffold {

	var $fields = array(
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

}