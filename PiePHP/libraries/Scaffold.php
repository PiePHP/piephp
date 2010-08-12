<?php

class Scaffold {

	public $type;

	public $table;

	public $singular;
	
	public $plural;

	public $category;

	public $fields = array();
	
	public $fieldsets;

	public $action;

	public $id;
	
	public $databaseConfigKey = 'default';
	
	function __construct($name, $action, $id) {

		if (!$this->table) {
			$this->table = strtolower(separate($name, '_'));
		}
		if (!$this->type) {
			$this->type = substr($this->table, 0, -1);
		}
		if (!$this->singular) {
			$this->singular = str_replace('_', ' ', $this->type);
		}
		if (!$this->plural) {
			$this->plural = str_replace('_', ' ', $this->table);
		}
		$this->action = $action;
		$this->id = $id;

		$fields = array();
		$this->fields['id'] = array(
			'type' => 'Id'
		);
		reset($this->fields);
		while (list($field_name, $settings) = each($this->fields)) {
			$class_name = $settings['type'] . 'Field';
			$settings['name'] = $field_name;
			$field = new $class_name($settings);
			$fields[$field_name] = $this->$field_name = $field;
		}
		$this->fields = $fields;
	}
	
	function getTitle() {
		if ($this->action == 'add') {
			return 'Add a ' . $this->singular;
		}
		else if ($this->action == 'change') {
			return 'Change a ' . $this->singular;
		}
		else if ($this->action == 'remove') {
			return 'Remove a ' . $this->singular;
		}
		else {
			return ucfirst($this->plural);
		}
	}

	function renderForm() {
		echo '<form enctype="multipart/form-data" action="' . $action . '">';
		if (!$this->fieldsets) {
			$this->fieldsets = array('' => array_keys($this->fields));
		}
		reset($this->fieldsets);
		while (list($legend, $field_names) = each($this->fieldsets)) {
			echo '<fieldset>';
			if ($legend) {
				echo '<h2>' . htmlentities($legend) . '</h2>';
			}
			reset($field_names);
			while (list(, $field_name) = each($field_names)) {
				$this->fields[$field_name]->renderFormField($formStyle);
			}
			echo '</fieldset>';
		}
	
		echo '</form>';
	}

	function renderList() {
		echo '<table>';
		echo 'table';
		echo '</table>';
	}
}