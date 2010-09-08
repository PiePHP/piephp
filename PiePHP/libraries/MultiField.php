<?php

class MultiField extends Field {

	public $fields = array();

	public $inline = false;

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

	public function renderInput() {
		foreach ($this->fields as $field) {
			$field->renderInput();
		}
	}

	public function renderListHeading() {
		foreach ($this->fields as $field) {
			$field->renderListHeading();
		}
	}

	public function renderListCell($isFirst) {
		foreach ($this->fields as $field) {
			$field->renderListCell($isFirst);
		}
	}

	public function setColumnValueOnScaffold() {
		foreach ($this->fields as $field) {
			$field->setColumnValueOnScaffold();
		}
	}

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