<?php

class MemcacheStatsController extends Controller {
	
	public $isCacheable = false;

	function indexAction() {
		$pageModel = new Model();
		$pageModel->cacheConfigName = 'default';
		$pageModel->cacheConnect();
		echo '<pre>';
		print_r($pageModel->cache->connection->getStats());
		echo '</pre>';
	}
}
