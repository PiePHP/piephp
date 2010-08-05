<?php

class FirstAndLastNameField extends Field {

	var $value = array('first' => '', 'last' => '');

	function renderInput() {
		$attributes = ' maxlength="32"' . ($this->required ? ' class="required"' : '');
		echo '<input type="text" name="first_' . $this->name . '"' . $attributes;
		if ($this->value['first']) {
			echo ' value="' . htmlentities($this->value['first']) . '"';
		}
		echo '>';
		echo '<input type="text" name="last_' . $this->name . '"' . $attributes;
		if ($this->value['last']) {
			echo ' value="' . htmlentities($this->value['last']) . '"';
		}
		echo '>';
	}

}