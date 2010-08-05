<?php

class IdField extends Field {

	function renderFormField() {
		echo '<input type="hidden" name="id" value="' . $this->value . '">';
	}

}