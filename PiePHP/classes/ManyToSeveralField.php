<?php
/**
 * A ManyToSeveralField uses checkboxes to maintain a many-to-many relation with a foreign scaffold.
 * It is assumed that the foreign scaffold's table will have fewer than 1000 records.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class ManyToSeveralField extends Field {

	/**
	 * The foreign scaffold controls the foreign table we're linking to.
	 * This property can be set to a scaffold name string in the constructor, then it
	 * becomes an object reference in the initialize method.
	 */
	public $foreignScaffold = NULL;

	/**
	 * For foreign tables with few records, we can maintain an association for displaying the
	 * records based on their IDs.
	 */
	public $foreignLabels = array();

	/**
	 * Some fields will have a set of ids that needs to be pulled from an external source.
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
		$ids = $this->getIds();
		echo '<fieldset>';
		foreach ($this->foreignLabels as $id => $label) {
			echo '<label class="option">';
				$checked = in_array($id, $ids) ? ' checked' : '';
				echo '<input type="checkbox" name="' . $this->name . '[]" value="' . $id . '"' .  $checked. '>';
				echo htmlentities($label);
			echo '</label>';
		}
		echo '</fieldset>';
	}

	/**
	 * Get the joined value of this field from POST data or from the Model.
	 * @return the value as a comma-delimited string of IDs.
	 */
	public function getValue() {
		$value = parent::getValue();
		if (is_array($value)) {
			$value = join(',', $value);
		}
		return $value;
	}

	/**
	 * Get the array of ids for this field from POST data or from the Model.
	 * @return an array of IDs.
	 */
	public function getIds() {
		$ids = parent::getValue();
		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}
		return $ids;
	}

	/**
	 * Render the value for this field in a data table.
	 */
	public function renderListCellValue() {
		$ids = $this->getIds();
		$labels = array();
		foreach ($ids as $id) {
			if (is_numeric($id)) {
				$labels[] = $this->foreignLabels[$id * 1];
			}
		}
		echo htmlentities(join(', ', $labels));
	}

}
