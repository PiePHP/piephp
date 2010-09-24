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

class SearchController extends Controller {

	/**
	 * Show search results.
	 */
	public function defaultAction() {
		$this->render(array(
			'title' => 'Search'
		));
	}
}
