<?php

class Scaffold {

	var $tableName = '';

	function __construct($record = array()) {
		$fields = array();
		$this->fields['id'] = array(
			'type' => 'Id'
		);
		reset($this->fields);
		while (list($field_name, $settings) = each($this->fields)) {
			$class_name = $settings['type'] . 'Field';
			$settings['name'] = $field_name;
			$field = new $class_name($settings);
			$fields[] = $this->$field_name = $field;
		}
		$this->fields = $fields;
	}

	function renderForm($action = 'add', $formStyle = 'form') {
		reset($this->fields);
		$html = '<form action="' . $action . '">';
		while (list(, $field) = each($this->fields)) {
			$html .= $field->renderFormField($formStyle);
		}
		$html .= '</form>';
		return $html;
	}
}