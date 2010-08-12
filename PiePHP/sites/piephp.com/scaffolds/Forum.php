<?php

class Forum extends Record {

	public $name;
	
	function __construct($assoc = array()) {
		$this->name = new Text($assoc['name']);
	}
}