<?php
/**
 * The section of the site that facilitates PiePHP downloads.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DownloadsController extends CachingController {

	/**
	 * Show the main downloads page.
	 */
	public function indexAction() {
		$data = array(
			'title' => 'Downloads'
		);
		$this->renderView('downloads/downloads', $data);
	}

	/**
	 * Show the page that contains information about the latest stable build.
	 */
	function latestAction() {
		$data = array(
			'title' => 'Latest stable build!'
		);
		$this->defaultTemplateName = 'veil';
		$this->renderView('downloads/latest', $data);
	}
}
