<?php

class MultiField extends Field {

	public $fields = array();

	function renderInput() {
		reset($this->fields);
		while (list(, $field) = each($this->fields)) {
			$field->renderInput();
		}
	}

}