<?php
/**
 * Respond to actions that result from URL resolution.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class Controller {

	/**
	 * The view template that is used if one is not provided.
	 * Note: templates come from the project's "/templates" directory and are suffixed with "_template.php"
	 */
	public $defaultTemplateName = 'base';

	/**
	 * If a propertyName is not provided to the loadModel method, the property will be $model.
	 */
	public $model;

	/**
	 * If a user is authenticated, we can access their session information here.
	 */
	public $session;

	/**
	 * If a className is not provided to the loadModel method, the default Model class will be used.
	 */
	public $defaultModelClassName = 'Model';

	/**
	 * If isCacheable is true, the buffer output will be cached when the page has finished processing.
	 */
	public $isCacheable = true;

	/**
	 * If the second part of the URL does not match a known action for the controller, its catchAllAction is called.
	 * This can be overridden if we wish to deal with arbitrary URLs.
	 */
	public function catchAllAction() {
		$errorsController = new ErrorsController();
		$errorsController->processError(404);
	}

	/**
	 * Include a view (by way of including a template if there is one).
	 * @param  $viewName: the name of the view to be included.
	 * @param  $data: optional data to be passed in to the view.
	 * @param  $templateName: optional template name to override the controller's default template name.
	 */
	public function renderView($viewName, $data = array(), $templateName = NULL) {
		global $APP_ROOT, $URL_ROOT, $HTTP_ROOT, $HTTPS_ROOT;

		// Put the data into variables that can be referred to within the scope of the template and view.
		if (is_array($data)) {
			reset($data);
			while (list($key, $value) = each($data)) {
				$$key = $value;
				if ($key == 'data') {
					break;
				}
			}
		}

		// Pass the view path in so that it can be used by a template if necessary.
		$viewPath = $APP_ROOT . 'views/' . $viewName . '_view.php';

		// If no template name was passed in, use the controller's default.
		if ($templateName === NULL) {
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

	/**
	 * Load one of this controller's properties with a model
	 * @param  $propertyName: optional property name to override the default property "model"
	 * @param  $className: optional class name to use instead of the default Model class.
	 * @return the model, in case we want to chain from the load call.
	 */
	public function loadModel($propertyName = NULL, $className = NULL) {
		if ($propertyName !== NULL) {
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
		return $this->$propertyName;
	}

	/**
	 * Use JavaScript or a location header to redirect to a given URL.
	 * @param  $url: the URL to redirect to.
	 * @param  $isMovedPermanently: whether to tell the client the page has moved permanently.
	 */
	public function sendRedirect($url, $isMovedPermanently = false) {
		// If this is an AJAX request, we must tell the window.
		if (is_ajax()) {
			echo "<script>window.location = '" . addslashes($url) . "'</script>";
		}
		else {
			header($isMovedPermanently ? 'HTTP/1.1 301 Moved Permanently' : 'HTTP/1.1 303 See other');
			header('Location: ' . $url);
		}
		exit;
	}

	/**
	 * Authenticate a user.
	 */
	public function authenticate() {
		$this->session = new Session();
		if (!$this->session->isSignedIn) {
			$this->sendRedirect($GLOBALS['HTTPS_ROOT'] . 'sign_in/');
		}
	}

}
