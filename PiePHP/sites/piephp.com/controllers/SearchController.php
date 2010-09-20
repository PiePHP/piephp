<?php
/**
 * A search engine will go here.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class SearchController extends NonCachingController {

	/**
	 * Show search results.
	 */
	public function indexAction() {
		$data = array(
			'title' => 'Search'
		);
		$this->renderView('search/search', $data);
	}
}