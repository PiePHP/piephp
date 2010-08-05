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
			$field = new $class_name();
			$field->name = $field_name;
			unset($settings['type']);
			while (list($setting, $value) = each($settings)) {
				$this->$field_name->$setting = $value;
			}
			$fields[] = $this->$field_name = $field;
		}
		$this->fields = $fields;
	}

	function renderForm($action = 'add', $formStyle = 'form') {
		reset($this->fields);
		echo '<form action="' . $action . '">';
		while (list(, $field) = each($this->fields)) {
			$field->renderFormField($formStyle);
		}
		echo '</form>';
	}
}