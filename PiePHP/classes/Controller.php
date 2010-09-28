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
	public $defaultTemplateName = 'default';

	/**
	 * If a propertyName is not provided to the loadModel method, the property will be $model.
	 */
	public $model;

	/**
	 * When we authenticate a user with the controller's authenticate method, we store the session information here.
	 */
	public $session;

	/**
	 * If a className is not provided to the loadModel method, the default Model class will be used.
	 */
	public $defaultModelClassName = 'Model';

	/**
	 * If useCaching is true, the buffer output will be cached when the page has finished processing.
	 * Caching can be turned on by overriding this property in the class definition, or it can be turned on inside
	 * a method because it the dispatcher checks for caching AFTER the controller action is called.
	 */
	public $useCaching = false;

	/**
	 * If useCaching is true, this is how long we will cache content.
	 */
	public $cacheTimeInSeconds = 60;

	/**
	 * If there is no default action defined, just catch it like we would catch any other undefined action.
	 */
	public function defaultAction() {
		$this->catchAllAction();
	}

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
		// We include the view and template in this function.
		// They'll want easy access to some globals.
		global $SITE_DIR;
		global $PIE_DIR;
		global $URL_ROOT;
		global $HTTP_ROOT;
		global $HTTPS_ROOT;
		global $ENVIRONMENT;
		global $VERSION;

		// Put the data into variables that can be referred to within the scope of the template and view.
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$$key = $value;
				if ($key == 'data') {
					break;
				}
			}
		}

		// Pass the view path in so that it can be used by a template if necessary.
		$viewPath = $SITE_DIR . 'views/' . $viewName . 'View.php';

		// If the view wasn't there, try falling back to the PiePHP default for this view.
		if (!file_exists($viewPath)) {
			$viewPath = $PIE_DIR . 'views/' . $viewName . 'View.php';
		}

		// If no template name was passed in, use the controller's default.
		if ($templateName === NULL) {
			$templateName = $this->defaultTemplateName;
		}

		// If there's a template, include it and let it include the view, otherwise, just include the view.
		if ($templateName) {
			include $SITE_DIR . 'templates/' . $templateName . 'Template.php';
		}
		else {
			include $viewPath;
		}

	}

	/**
	 * Infer a view based on the controller and action, and render that view.
	 * @param  $data: optional data to be passed in to the view.
	 * @param  $templateName: optional template name to override the controller's default template name.
	 */
	public function render($data = array(), $templateName = NULL) {
		global $CONTROLLER_NAME;
		global $ACTION_NAME;

		// Slice "Controller" off the end of the controller name, and lowercase the first letter.
		$controller = substr($CONTROLLER_NAME, 0, -10);
		$controller[0] = strtolower($controller[0]);

		// Slice "Action" off the end of the action name.
		$action = substr($ACTION_NAME, 0, -6);

		// For example, the MyAccountController's profileSettingsAction will have the viewName "myAccount_profileSettings".
		// This viewName will resolve to the file: "/views/myAccount_profileSettingsView.php"
		$viewName = $controller . '_' . $action;

		$this->renderView($viewName, $data, $templateName);
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
	 * Set a combination of headers to prevent clients from caching the response.
	 */
	public function preventCaching() {
		/*
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		*/
	}

	/**
	 * Use a location header to redirect to a given URL.
	 * @param  $url: the URL to redirect to.
	 * @param  $isMovedPermanently: whether to tell the client the page has moved permanently.
	 */
	public function sendRedirect($url, $isMovedPermanently = false) {
		if (is_ajax()) {
			$url .= (strpos($url, '?') == false ? '?' : '&') . 'isAjax=1';
		}
		header($isMovedPermanently ? 'HTTP/1.1 301 Moved Permanently' : 'HTTP/1.1 303 See other');
		header('Location: ' . $url);
		exit;
	}

	/**
	 * Use JavaScript or a location header to redirect to a given URL.
	 * @param  $url: the URL to redirect to.
	 */
	public function sendJsRedirect($url) {
		$scope = is_frame() ? 'parent' : 'window';
		echo "<script>$scope.location = '" . addslashes($url) . "'</script>";
		exit;
	}

	/**
	 * Authenticate a user.
	 */
	public function authenticate() {
		$this->session = new Session();
		if (!$this->session->isSignedIn) {
			$signInController = new SignInController();
			$signInController->defaultAction();
			exit;
		}
	}

}
