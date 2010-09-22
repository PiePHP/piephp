<?php
/**
 * Display cache stats and allow flushing.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CacheToolsController extends Controller {

	/**
	 * If there is more than one cache, display the list of caches.
	 */
	public function indexAction() {
		global $CACHES;
		if (count($CACHES) == 1) {
			$names = array_keys($CACHES);
			$this->statsAction($names[0]);
			exit;
		}
		$this->renderView('cache_tools/caches', array(
			'title' => 'Memcache caches',
			'caches' => $CACHES
		));
	}

	/**
	 * Display stats for a specific cache.
	 * @param $cacheName: the name of the cache for which we would like to display stats.
	 */
	public function statsAction($cacheName) {
		$stats = $this->loadModel()->loadCache($cacheName)->getStats();
		$this->renderView('cache_tools/stats', array(
			'title' => 'Cache stats',
			'cacheName' => $cacheName,
			'stats' => $stats
		));
	}

	/**
	 * Flush a specific cache.
	 * @param $cacheName: the name of the cache we would like to flush.
	 */
	public function flushAction($cacheName) {
		$this->loadModel()->loadCache($cacheName)->flush();
		$this->indexAction();
	}
}
