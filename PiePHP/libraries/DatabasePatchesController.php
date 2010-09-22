<?php
/**
 * Search for database patches in models/database_patches, and run any that are found.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class DatabasePatchesController extends Controller {

	/**
	 * Search for database patches in models/database_patches, and run any that are found.
	 */
	public function indexAction() {
		global $DATABASES;
    global $APP_ROOT;
		$this->authenticate();

		$results = array();
		foreach ($DATABASES as $databaseName => $config) {
			$results[$databaseName] = array();
			$model = $this->loadModel();
			$model->loadDatabase($databaseName);
			$model->ignoreErrors = true;
			$nextOrdinal = $model->selectValue('MAX(ordinal) FROM patches') + 1;
			$model->ignoreErrors = false;

			while (file_exists($path = $APP_ROOT . 'models/database_patches/' . $databaseName . '/patch' . sprintf('%05d', $nextOrdinal) . '.sql')) {
				$patched = true;
				//echo 'Running ' . $path . '...<br/>';
				$statements = preg_split('/;[\r\n]/', file_get_contents($path));
				for ($i = 0; $i < count($statements); $i++) {
					$statement = trim($statements[$i]);
					if (trim($statement)) {
						$model->execute($statement);
					}
          $results[$databaseName][] = $path;
				}
				$model->execute('UPDATE patches SET ordinal = ' . $nextOrdinal++);
			}
		}

		$this->renderView('database_patches/database_patches', array(
			'title' => 'Database patches',
			'results' => $results
		));
	}

}
