<?php
/**
 * Manage user groups.
 * TODO: Make user groups editable only by a Superuser or Developer.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class UserGroupsScaffold extends Scaffold {

	/**
	 * A user group must have a name.
	 */
	public $fields = array(
		'name' => array(
			'type' => 'Name',
			'required' => true
		)
	);

}