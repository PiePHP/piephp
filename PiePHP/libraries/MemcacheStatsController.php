<?php

class MemcacheStatsController extends Controller {

	function indexAction() {
		$pageModel = new Model();
		$pageModel->cacheConfigName = 'default';
		$pageModel->cacheConnect();
		echo '<pre>';
		print_r($pageModel->cache->connection->getStats());
		echo '</pre>';
	}
}
