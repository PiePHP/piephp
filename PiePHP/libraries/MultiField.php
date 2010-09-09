<?php
/**
 * A field can be a combination of several fields (such as FirstAndLastNameField)
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class MultiField extends Field {

	/**
	 * The component fields that make up this field.
	 */
	public $fields = array();

	/**
	 * Whether to display the fields on the same line in a form.
	 */
	public $inline = false;

	/**
	 * Render the field in a form.
	 */
	public function renderFormField() {
		if ($this->inline) {
			parent::renderFormField();
		}
		else {
			echo '<div>';
			foreach ($this->fields as $field) {
				$field->renderFormField();
			}
			echo '</div>';
		}
	}

	/**
	 * Render the inputs for each component field in this field.
	 */
	public function renderInput() {
		foreach ($this->fields as $field) {
			$field->renderInput();
		}
	}

	/**
	 * Render the headings for each component field's column in a data table.
	 */
	public function renderListHeading() {
		foreach ($this->fields as $field) {
			$field->renderListHeading();
		}
	}

	/**
	 * Render the headings for each component field's column in a data table.
	 * @param  $isFirst: whether this is the first cell in its row.
	 */
	public function renderListCell($isFirst) {
		foreach ($this->fields as $field) {
			$field->renderListCell($isFirst);
		}
	}

	/**
	 * Set the values of component fields on the scaffold for processing in the Model.
	 */
	public function setColumnValueOnScaffold() {
		foreach ($this->fields as $field) {
			$field->setColumnValueOnScaffold();
		}
	}

	/**
	 * Check the field for validity.
	 * @return true if the field is valid.
	 */
	public function isValid() {
		$isValid = true;
		foreach ($this->fields as $field) {
			if (!$field->isValid()) {
				$isValid = false;
			}
		}
		return $isValid;
	}

}