<?php
/**
 * A ManyToManyField uses checkboxes to maintain a many-to-many relation with another scaffold.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class ManyToManyField extends Field {

	/**
	 * The foreign scaffold controls the foreign table we're linking to.
	 * This property can be set to a scaffold name string in the constructor, then it
	 * becomes an object reference in the initialize method.
	 */
	public $foreignScaffold = NULL;

	/**
	 * This is the name of the table that will contain foreign keys to this field's scaffold's
	 * table and the foreign scaffold's table.
	 */
	public $junctionTable = NULL;

	/**
	 * For foreign tables with few values, we can maintain an association for displaying the
	 * values based on their IDs.
	 */
	public $foreignLabels = array();

	/**
	 * Some fields will have a set of values that needs to be pulled from an external source.
	 */
	public function initialize() {

		// If we don't have a foreign scaffold name like "UserGroupsScaffold", we can infer it
		// from a field name like "userGroups".
		if (!$this->foreignScaffold) {
			$this->foreignScaffold = ucfirst($this->name) . 'Scaffold';
		}

		$foreignScaffoldName = $this->foreignScaffold;
		$this->foreignScaffold = new $foreignScaffoldName();

		$this->foreignLabels = $this->foreignScaffold->getLabelsForForeignRelations();
	}

	/**
	 * Render checkboxes for selecting many records.
	 */
	public function renderInput() {
		echo '<fieldset>';
		foreach ($this->foreignLabels as $id => $label) {
			echo '<label class="option">';
				echo '<input type="checkbox" name="' . $this->name . '[]" value="' . $id . '">';
				echo htmlentities($label);
			echo '</label>';
		}
		echo '</fieldset>';
	}

	/**
	 * Get the value of this field from POST data or from the Model.
	 * @return the POSTed or stored value.
	 */
	public function getValue() {
		$value = parent::getValue();
		return explode(',', $value);
	}

}
