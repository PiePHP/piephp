<?php
/**
 * Find patches that are newer than the last executed patch, and run them.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class PatchesController extends Controller {

	/**
	 * Search for patches in models/patches, and run any that are found.
	 */
	public function defaultAction() {
		global $ENVIRONMENT;
		global $DATABASES;
		global $SITE_DIR;
		if ($ENVIRONMENT != 'development') {
			$this->authorize(array(
				1, // System administrators
			));
		}

		$results = array();
		foreach ($DATABASES as $databaseName => $config) {
			$results[$databaseName] = array();
			$model = $this->loadModel();
			$model->loadDatabase($databaseName);
			$model->ignoreErrors = true;
			$nextOrdinal = $model->selectValue('MAX(ordinal) FROM patches') + 1;
			$model->ignoreErrors = false;

			$patchesDir = $SITE_DIR . 'data/patches/' . $databaseName . 'Database/';

			while (file_exists($path = $patchesDir . 'patch' . sprintf('%05d', $nextOrdinal) . '.sql')) {
				$patched = true;
				//echo 'Running ' . $path . '...<br/>';
				$statements = preg_split('/;[\r\n]/', file_get_contents($path));
				for ($i = 0; $i < count($statements); $i++) {
					$statement = trim($statements[$i]);
					if (trim($statement)) {
						$model->execute($statement);
					}
				}
				$results[$databaseName][] = $path . ' (' . $i . ' statements)';
				$model->execute('UPDATE patches SET ordinal = ' . $nextOrdinal++);
			}
		}

		$this->render(array(
			'results' => $results
		));
	}

}
