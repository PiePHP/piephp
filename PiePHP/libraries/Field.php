<?php

class Field {

	var $required = false;

	var $value = '';

	function __construct($settings) {
		reset($settings);
		unset($settings['type']);
		while (list($setting, $value) = each($settings)) {
			$this->$setting = $value;
		}
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
		echo ' class="' . ($this->required ? 'required ' : '') . $this->type . '"';
	}

	function renderTip() {
	}

}