<?php

class UsernameField extends Field {

	public $maxlength = 32;

	function getDefaultAdvice() {
		return parent::getDefaultAdvice() . '<br>It can only have letters, numbers and ".", "-" or "_".';
	}

}