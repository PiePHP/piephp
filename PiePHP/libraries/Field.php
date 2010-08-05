<?php

class Field {

	var $required = false;

	var $value = '';

	function __construct($value = '') {
		//die('construct Field');
		$this->value = $value;
	}

	function renderFormField() {
		echo '<fieldset>';
			echo '<label>';
				$this->renderLabel();
			echo '</label>';
			$this->renderInput();
			$this->renderTip();
		echo '</fieldset>';
	}

	function renderLabel() {
		echo ucfirst(separate($this->name, ' '));
	}

	function renderInput() {
		echo '<input type="' . $this->type . '" name="' . $this->name . '"';
		if ($this->length) {
			echo ' maxlength="' . $this->length . '"';
		}
		$this->renderClass();
		if ($this->value) {
			echo ' value="' . htmlentities($this->value) . '"';
		}
		echo '>';
	}

	function renderClass() {
		if ($this->required) {
			echo ' class="required"';
		}
	}

	function renderTip() {
	}

}