<?php

class Forum extends Record {

	var $name;
	
	function __construct($assoc = array()) {
		$this->name = new Text($assoc['name']);
	}
}