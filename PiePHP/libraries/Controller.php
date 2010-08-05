<?php

class Controller {

	var $defaultTemplateName = 'base';

	function renderView($view_name, $data = array(), $template_name = false) {

		// Put the data into variables that can be referred to within the scope of the template and view.
		if (is_array($data)) {
			reset($data);
			while (list($key, $value) = each($data)) {
				$$key = $value;
			}
		}

		// Put the default view parameters into the scope of the template and view.
		global $VIEW_PARAMS;
		if (is_array($VIEW_PARAMS)) {
			reset($VIEW_PARAMS);
			while (list($key, $value) = each($VIEW_PARAMS)) {
				$$key = $value;
			}
		}

		// Pass the view path in so that it can be used by a template if necessary.
		$view_path = APP_ROOT . 'views/' . $view_name . '_view.php';

		// If no template name was passed in, use the controller's default.
		if ($template_name === false) {
			$template_name = $this->defaultTemplateName;
		}

		// If there's a template, include it and let it include the view, otherwise, just include the view.
		if ($template_name) {
			include APP_ROOT . 'templates/' . $template_name . '_template.php';
		}
		else {
			include $view_path;
		}

	}

	function loadModel($property_name) {
		$class_name = ucfirst($property_name);
		$this->$property_name = new $class_name();
	}

}
