<?php

class MemcacheStatsController extends Controller {

	function index()
	{
		$page_model = new Model();
		$page_model->cacheConfigKey = 'default';
		$page_model->cacheConnect();
		echo '<pre>';
		print_r($page_model->cache->connection->getStats());
		echo '</pre>';
	}
}
