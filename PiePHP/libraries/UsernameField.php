<?php

class UsernameField extends Field {

	public $maxlength = 32;

	public function getDefaultAdvice() {
		return parent::getDefaultAdvice() . '<br>It can only have letters, numbers and ".", "-" or "_".';
	}

}