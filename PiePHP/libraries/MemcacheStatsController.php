<?php
/**
 * Display Memcache stats in a page.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class MemcacheStatsController extends NonCachingController {

	/**
	 * Display Memcache stats.
	 */
	public function indexAction() {
		$pageModel = new Model();
		$pageModel->cacheConfigName = 'default';
		$pageModel->cacheConnect();
		echo '<pre>';
		print_r($pageModel->cache->connection->getStats());
		echo '</pre>';
	}
}
