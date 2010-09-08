<?php

class PhpinfoController extends NonCachingController {

	public function indexAction() {
		phpinfo();
	}
}
