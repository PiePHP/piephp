<?php

class Scaffold extends Controller {

	/**
	 * The type of data this scaffold manipulates. (e.g. 'blog_post')
	 */
	public $type;

	/**
	 * The database table corresponding to this scaffold's type. (e.g. 'blog_posts')
	 */
	public $table;

	/**
	 * The user-facing word for one object of this type. (e.g. 'blog post')
	 */
	public $singular;

	/**
	 * The user-facing word for many objects of this type. (e.g. 'blog posts')
	 */
	public $plural;

	/**
	 * The set of fields which this type contains.
	 * These don't necessarily correspond one-to-one with database columns.
	 */
	public $fields = array();

	/**
	 *
	 */
	public $fieldsets;

	/**
	 *
	 */
	public $action;

	/**
	 *
	 */
	public $submit;

	/**
	 *
	 */
	public $id;

	/**
	 *
	 */
	public $path;

	/**
	 *
	 */
	public $result;

	/**
	 *
	 */
	public $columnValues;

	/**
	 *
	 */
	public $hasValidationErrors = false;

	function __construct($name, $action = 'list', $id = 0) {
		if (!$this->table) {
			$this->table = strtolower(separate($name, '_'));
		}
		if (!$this->type) {
			$this->type = substr($this->table, 0, -1);
		}
		if (!$this->singular) {
			$this->singular = separate($this->type, ' ');
		}
		if (!$this->plural) {
			$this->plural = separate($this->table, ' ');
		}
		$this->action = $action ? $action : 'list';
		$this->submit = isset($_POST['submit']) ? $_POST['submit'] : NULL;
		$this->id = $id * 1;

		if (!isset($this->listFields)) {
			$this->listFields = array_keys($this->fields);
		}
		$fields = array();

		foreach ($this->fields as $fieldName => $settings) {
			$className = $settings['type'] . 'Field';
			$settings['name'] = $fieldName;
			$field = new $className($settings, $this);
			$fields[$fieldName] = $field;
		}
		$this->fields = &$fields;
		$this->path = preg_replace('/(add|change|remove)\/([0-9]+\/?)?$/', '', $GLOBALS['PAGE_PATH']);
		$this->getResult();
	}

	function processPost() {
		if (count($_POST)) {

			$this->validate();
			if ($this->hasValidationErrors) {
				return;
			}

			$this->loadModel();
			$this->model->beginTransaction();

			foreach ($this->fields as $field) {
				$field->processBeforeScaffold();
			}
			if ($this->action == 'add' || $this->submit == 'new') {
				$this->processInsert();
			}
			else if ($this->action == 'change') {
				$this->processUpdate();
			}
			else if ($this->action == 'remove') {
				$this->processDelete();
			}
			$this->sendRedirect($this->getRedirectUrl());

			foreach ($this->fields as $field) {
				$field->processAfterScaffold();
			}

			$this->model->commitTransaction();
		}
	}

	function validate() {
		foreach ($this->fields as $field) {
			$field->validate();
		}
	}

	function processInsert() {
		$columnValues = $this->getPostedColumnValues();
		$this->model->insert($this->table, $columnValues);
	}

	function processUpdate() {
		$columnValues = $this->getPostedColumnValues();
		$this->model->update($this->table, $columnValues, $this->id);
	}

	function processDelete() {
		$this->model->delete($this->table, $this->id);
	}

	function getPostedColumnValues() {
		$this->columnValues = array();
		foreach ($this->fields as $field) {
			$field->setColumnValueOnScaffold();
		}
		return $this->columnValues;
	}

	function getRedirectUrl() {
		$redirectUrl = $GLOBALS['HTTP_BASE'] . $this->path;

		// If the user selected to save and add another, then take them to an add page.
		if (strpos($this->submit, 'add') !== false) {
			$redirectUrl .= 'add/';
		}

		if (is_ajax()) {
			$redirectUrl .= '?is_ajax=1';
		}
		return $redirectUrl;
	}

	function getResult() {
		if ($this->id) {
			$this->loadModel();
			$this->result = $this->model->result("SELECT * FROM $this->table WHERE id = " . ($this->id * 1));
		}
	}

	function getTitle() {
		if ($this->action == 'add') {
			return 'Add a ' . $this->singular;
		}
		else if ($this->action == 'change') {
			if ($this->result) {
				return 'Change a ' . $this->singular;
			}
			else {
				return 'Unknown ' . $this->singular;
			}
		}
		else if ($this->action == 'remove') {
			return 'Remove ' . $this->singular;
		}
		else {
			return ucfirst($this->plural);
		}
	}

	function renderAddForm() {
		$this->renderForm();
	}

	function renderChangeForm() {
		if (!$this->result) {
			$this->renderDoesntExistMessage();
		}
		else {
			$this->renderForm();
		}
	}

	function renderForm() {
		$this->renderFormStart();
		$this->renderFormFieldsets();
		$this->renderFormEnd();
	}

	function renderList() {
		$this->renderFormStart();
		echo '<table>';
		echo '<tr>';
			echo '<th class="first"><input type="checkbox" name="id"></th>';
			foreach ($this->listFields as $fieldName) {
				$this->fields[$fieldName]->renderListHeading();
			}
		echo '</tr>';
		$this->loadModel();
		$results = $this->model->results("SELECT * FROM $this->table LIMIT 100");
		foreach ($results as $resultIndex => $result) {
			$this->result = $result;
			echo '<tr class="' . ($resultIndex % 2 ? 'odd' : 'even') . '">';
				echo '<td class="first"><input type="checkbox" name="id"></td>';
				foreach ($this->listFields as $fieldIndex => $fieldName) {
					$isFirst = $fieldIndex < 1;
					$this->fields[$fieldName]->renderListCell($isFirst);
				}
			echo '</tr>';
		}
		echo '</table>';
		$this->renderFormEnd();
	}

	function renderRemovalConfirmation() {
		if (!$this->result) {
			$this->renderDoesntExistMessage();
		}
		else {
			$this->renderFormStart();
			echo '<div>';
			echo 'Are you sure you want to remove this ' . $this->singular . '?';
			echo '</div>';
			echo '<br>';
			$this->renderFormEnd();
		}
	}

	function renderDoesntExistMessage() {
		echo "The " . $this->singular . " you're trying to " . $this->action . " doesn't exist.";
	}

	function renderFormStart() {
		$action = $this->path . $this->action . '/';
		if ($this->id) {
			$action .= $this->id . '/';
		}
		echo '<form enctype="multipart/form-data" action="' . $action . '" method="post" class="scaffold">';
	}

	function renderFormEnd() {
		echo '<div class="actions">';
		if ($this->action == 'remove') {
			echo '<div>';
				echo '<button type="submit" name="submit" value="remove" class="main"><b>Remove</b></button>';
			echo '</div>';
		}
		else if ($this->action == 'list') {
			echo '<a href="' . $this->path . 'add/" class="add">Add a ' . $this->singular . '</a>';
		}
		else {
			if ($this->action == 'change') {
				echo '<a href="' . $this->path . 'remove/' . $this->id . '/" class="remove">Remove</a>';
			}
			echo '<div>';
				if ($this->action == 'change') {
					echo '<button type="submit" name="submit" value="new"><b>Save as new</b></button>';
				}
				echo '<button type="submit" name="submit" value="save_add"><b>Save and add another</b></button>';
				echo '<button type="submit" name="submit" value="save" class="main"><b>Save</b></button>';
			echo '</div>';
		}
		echo '</div>';
		echo '</form>';
	}

	function renderFormFieldsets() {
		if (!isset($this->fieldsets)) {
			$this->fieldsets = array('' => array_keys($this->fields));
		}
		foreach ($this->fieldsets as $heading => $fieldNames) {
			echo '<fieldset>';
			if ($heading) {
				echo '<h2>' . htmlentities($heading) . '</h2>';
			}
			foreach ($fieldNames as $fieldName) {
				$this->fields[$fieldName]->renderFormField();
			}
			echo '</fieldset>';
		}
	}
}
