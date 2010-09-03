<?php

class PhpinfoController extends Controller {
	
	public $isCacheable = false;

	function indexAction() {
		phpinfo();
	}
}
