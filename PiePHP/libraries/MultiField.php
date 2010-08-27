<?php

class MultiField extends Field {

	public $fields = array();

	public $inline = false;

	function renderFormField() {
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

	function renderInput() {
		foreach ($this->fields as $field) {
			$field->renderInput();
		}
	}

	function renderListHeading() {
		foreach ($this->fields as $field) {
			$field->renderListHeading();
		}
	}

	function renderListCell($isFirst) {
		foreach ($this->fields as $field) {
			$field->renderListCell($isFirst);
		}
	}

	function setColumnValue(&$columnValues) {
		foreach ($this->fields as $field) {
			$field->setColumnValue($columnValues);
		}
	}

	function isValid() {
		$isValid = true;
		foreach ($this->fields as $field) {
			if (!$field->isValid()) {
				$isValid = false;
			}
		}
		return $isValid;
	}

}