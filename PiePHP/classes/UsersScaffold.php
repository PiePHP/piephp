<?php
/**
 * Manage users.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class UsersScaffold extends Scaffold {

	/**
	 * Users have a name, email, username and password.
	 */
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
		),
		'userGroups' => array(
			'type' => 'ManyToSeveral',
			'foreignScaffold' => 'UserGroupsScaffold'
		)
	);

	/**
	 * User forms have 2 sections: one for general information, and one for authentication information.
	 */
	public $fieldsets = array(
		'Personal information' => array('name', 'email'),
		'Authentication' => array('username', 'password', 'userGroups'),
	);

	/**
	 * In a list of records, we will see all fields except passwords.
	 */
	public $listFields = array('username', 'name', 'email', 'userGroups');

}