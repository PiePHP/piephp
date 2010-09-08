<?php

class DownloadsController extends Controller {

	public function indexAction() {
		$data = array(
			'title' => 'Downloads'
		);
		$this->renderView('downloads/downloads', $data);
	}

	function latestAction() {
		$data = array(
			'title' => 'Latest stable build!'
		);
		$this->defaultTemplateName = 'veil';
		$this->renderView('downloads/latest', $data);
	}
}
