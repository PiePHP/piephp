<?php

class MemcacheStatsController extends NonCachingController {

	public function indexAction() {
		$pageModel = new Model();
		$pageModel->cacheConfigName = 'default';
		$pageModel->cacheConnect();
		echo '<pre>';
		print_r($pageModel->cache->connection->getStats());
		echo '</pre>';
	}
}
