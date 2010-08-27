<?php

class Controller {

	public $defaultTemplateName = 'base';

	public $model;

	public $defaultModelClassName = 'Model';

	function catchAllAction() {
		$errorsController = new ErrorsController();
		$errorsController->processError(404);
	}

	function renderView($viewName, $data = array(), $templateName = false) {
		global $APP_ROOT, $HTTP_ROOT, $HTTPS_ROOT;

		// Put the data into variables that can be referred to within the scope of the template and view.
		if (is_array($data)) {
			reset($data);
			while (list($key, $value) = each($data)) {
				$$key = $value;
			}
		}

		// Pass the view path in so that it can be used by a template if necessary.
		$viewPath = $APP_ROOT . 'views/' . $viewName . '_view.php';

		// If no template name was passed in, use the controller's default.
		if ($templateName === false) {
			$templateName = $this->defaultTemplateName;
		}

		// If there's a template, include it and let it include the view, otherwise, just include the view.
		if ($templateName) {
			include $APP_ROOT . 'templates/' . $templateName . '_template.php';
		}
		else {
			include $viewPath;
		}

	}

	function loadModel($propertyName = NULL, $className = NULL) {
		if ($propertyName) {
			$className = ucfirst($propertyName);
		}
		else {
			$propertyName = 'model';
			$className = $this->defaultModelClassName;
		}
		if (!isset($this->$propertyName)) {
			$className = ucfirst($propertyName);
			$this->$propertyName = new $className();
		}
	}

	function sendRedirect($url, $moved = false) {
		header($moved ? 'HTTP/1.1 301 Moved Permanently' : 'HTTP/1.1 303 See other');
		header('Location: ' . $url);
		exit;
	}

	function renderRefresher() {
		global $HTTP_ROOT;
		if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || substr($_SERVER['REMOTE_ADDR'], 0, 7) == '192.168') {
			?>
			<iframe src="<?php echo $HTTP_ROOT; ?>refresher" style="display:none"></iframe>
			<?php
		}
	}

}
