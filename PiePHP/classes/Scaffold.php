<?php
/**
 * A Scaffold renders result lists and forms for view/add/change/delete actions on a database table.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

abstract class Scaffold extends Controller {

	/**
	 * The type of data this scaffold manipulates. (e.g. "blog_post")
	 */
	public $type;

	/**
	 * The database table corresponding to this scaffold's type. (e.g. "blog_posts")
	 */
	public $table;

	/**
	 * The user-facing word for one object of this type. (e.g. "blog post")
	 */
	public $singular;

	/**
	 * The user-facing word for many objects of this type. (e.g. "blog posts")
	 */
	public $plural;

	/**
	 * The set of fields which this type contains.
	 * These don't necessarily correspond one-to-one with database columns.
	 */
	public $fields = array();

	/**
	 * If forms need to be broken into multiple sets of fields, fieldsets should be populated as an associative array.
	 * The array keys are fieldset legends, and the values are arrays of field names.
	 */
	public $fieldsets;

	/**
	 * The action which the scaffold is currently performing, like "list", "add", "change" or "remove".
	 */
	public $action;

	/**
	 * The ID of the record that the scaffold is currently operating on.
	 */
	public $id;

	/**
	 * The database record that the scaffold is currently operating on.
	 */
	public $result;

	/**
	 * The value of the submit button that was pressed to submit the form (if it has been submitted).
	 */
	public $submitButtonValue;

	/**
	 * The URL path of this scaffold. (e.g. /admin/users/)
	 */
	public $urlRoot;

	/**
	 * An associative array with database column names for keys and POST data for values.
	 */
	public $columnValues;

	/**
	 * True if any of the scaffold's fields were found to be invalid upon validation.
	 */
	public $hasValidationErrors = false;

	/**
	 * A column name or SQL expression used for human-readable identification of a record
	 * when one or more records are to be selectable from another scaffold.
	 */
	public $labelForForeignRelations;

	/**
	 * Create a scaffold by name, and tell it what it's supposed to do in this request.
	 * @param  $name: the name of the scaffold.
	 * @param  $action: what it's supposed to do.
	 * @param  $id: which record it's supposed to act on.
	 */
	public function __construct($action = 'list', $id = 0) {
		global $URL_PATH;

		// Example: "UserGroupsScaffold".
		$className = get_class($this);

		// Example: "UserGroups".
		$name = substr($className, 0, -8);

		// Example: "user_groups".
		if (!$this->table) {
			$this->table = strtolower(separate($name, '_'));
		}

		// Example: "user_group".
		if (!$this->type) {
			$this->type = substr($this->table, 0, -1);
		}

		// Example: "user group".
		if (!$this->singular) {
			$this->singular = separate($this->type, ' ');
		}

		// Example: "user groups".
		if (!$this->plural) {
			$this->plural = separate($this->table, ' ');
		}

		// The default action is "list".
		$this->action = $action ? $action : 'list';

		// The id must be made numeric to avoid SQL injection.
		$this->id = $id * 1;

		// We need to know whether the button used to submit was "Save" or "Save as new" or something else.
		$this->submitButtonValue = isset($_POST['submit']) ? $_POST['submit'] : NULL;

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

		// Example: "user groups".
		if (!$this->labelForForeignRelations) {
			$this->labelForForeignRelations = $this->fields[$this->listFields[0]]->column;
		}

		$this->urlRoot = preg_replace('/(add|change|remove)\/([0-9]+\/?)?(\?.*)?$/', '', $URL_PATH);
		$this->getResult();
	}

	/**
	 * If the form's data is valid, write it to the database.
	 */
	public function processPost() {
		if (count($_POST)) {

			if ($this->action == 'add' || $this->submitButtonValue == 'change') {
				$this->validate();
				if ($this->hasValidationErrors) {
					return;
				}
			}

			$this->loadModel();
			$this->model->beginTransaction();

			foreach ($this->fields as $field) {
				$field->processBeforeScaffold();
			}
			if ($this->action == 'add' || $this->submitButtonValue == 'new') {
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

	/**
	 * Assess the validity of each of this scaffold's field.
	 */
	public function validate() {
		foreach ($this->fields as $field) {
			$field->validate();
		}
	}

	/**
	 * Use the model to perform a database insert.
	 */
	public function processInsert() {
		$columnValues = $this->getPostedColumnValues();
		$this->model->insert($this->table, $columnValues);
	}

	/**
	 * Use the model to perform a database update.
	 */
	public function processUpdate() {
		$columnValues = $this->getPostedColumnValues();
		$this->model->update($this->table, $columnValues, $this->id);
	}

	/**
	 * Use the model to perform a database delete.
	 */
	public function processDelete() {
		$this->model->delete($this->table, $this->id);
	}

	/**
	 * Get the posted values for each database column that this scaffold's fields represent.
	 * @return
	 */
	public function getPostedColumnValues() {
		$this->columnValues = array();
		foreach ($this->fields as $field) {
			$field->setColumnValueOnScaffold();
		}
		return $this->columnValues;
	}

	/**
	 * Get the URL for the appropriate redirect location upon processing a submit.
	 * @return the URL as a string.
	 */
	public function getRedirectUrl() {
		$redirectUrl = $GLOBALS[is_https() ? 'HTTPS_BASE' : 'HTTP_BASE'] . $this->urlRoot;

		// If the user selected to save and add another, then take them to an add page.
		if (strpos($this->submitButtonValue, 'add') !== false) {
			$redirectUrl .= 'add/';
		}

		if (is_ajax()) {
			$redirectUrl .= '?isAjax=1';
		}
		return $redirectUrl;
	}

	/**
	 * If the scaffold was created with an ID, get the corresponding result from the database.
	 */
	public function getResult() {
		if ($this->id) {
			$this->loadModel();
			$this->result = $this->model->selectAssoc("* FROM $this->table WHERE id = " . ($this->id * 1));
		}
	}

	/**
	 * Get the title for the page title for this scaffold and it's current action.
	 * @return the title as a string.
	 */
	public function getTitle() {
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

	/**
	 * Render a form for inserting a record into the database.
	 */
	public function renderAddForm() {
		$this->renderForm();
	}

	/**
	 * Render a form for updating a record in the database.
	 */
	public function renderChangeForm() {
		if (!$this->result) {
			$this->renderDoesntExistMessage();
		}
		else {
			$this->renderForm();
		}
	}

	/**
	 * Render an add form or a change form.
	 */
	public function renderForm() {
		$this->renderFormStart();
		$this->renderFormFieldsets();
		$this->renderFormEnd();
	}

	/**
	 * Render a list of records and selected field values in a table.
	 */
	public function renderList() {
		$this->renderFormStart();
		echo '<table>';
		echo '<tr>';
			// TODO: Implement actions on multiple records.
			//echo '<th class="first"><input type="checkbox" name="id"></th>';
			foreach ($this->listFields as $fieldName) {
				$this->fields[$fieldName]->renderListHeading();
			}
		echo '</tr>';
		$this->loadModel();
		$results = $this->model->select("* FROM $this->table LIMIT 100");
		foreach ($results as $resultIndex => $result) {
			$this->result = $result;
			echo '<tr class="' . ($resultIndex % 2 ? 'odd' : 'even') . '">';
				// TODO: Implement actions on multiple records.
				//echo '<td class="first"><input type="checkbox" name="id[]"></td>'; 
				foreach ($this->listFields as $fieldIndex => $fieldName) {
					$isFirst = $fieldIndex < 1;
					$this->fields[$fieldName]->renderListCell($isFirst);
				}
			echo '</tr>';
		}
		echo '</table>';
		$this->renderFormEnd();
	}

	/**
	 * Render a form for confirming that a record should be deleted.
	 */
	public function renderRemovalConfirmation() {
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

	/**
	 * Render a message saying that a record you were trying to operate on does not exist, in case of concurrency issues.
	 */
	public function renderDoesntExistMessage() {
		echo "The " . $this->singular . " you're trying to " . $this->action . " doesn't exist.";
	}

	/**
	 * Render the form's start tag.
	 */
	public function renderFormStart() {
		$action = $this->urlRoot . $this->action . '/';
		if ($this->id) {
			$action .= $this->id . '/';
		}
		echo '<form enctype="multipart/form-data" action="' . $action . '" method="post" class="scaffold">';
	}

	/**
	 * Render the form's submit buttons and end tag.
	 */
	public function renderFormEnd() {
		echo '<div class="actions">';
		if ($this->action == 'remove') {
			echo '<div>';
				echo '<button type="submit" name="submit" value="remove" class="main"><b>Remove</b></button>';
			echo '</div>';
		}
		else if ($this->action == 'list') {
			echo '<a href="' . $this->urlRoot . 'add/" class="add">Add a ' . $this->singular . '</a>';
		}
		else {
			if ($this->action == 'add') {
				echo '<a href="' . $this->urlRoot . '" class="cancel">Cancel</a>';
			}
			elseif ($this->action == 'change') {
				echo '<a href="' . $this->urlRoot . 'remove/' . $this->id . '/" class="remove">Remove</a>';
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

	/**
	 * Render the fieldsets that make up a form.
	 */
	public function renderFormFieldsets() {
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

	/**
	 * If this scaffold's data will be used to select relations to it, the relating scaffold must have strings
	 * to describe the related records.
	 * @return an associative array relating IDs to labels.
	 */
	public function getLabelsForForeignRelations() {
		$this->loadModel();
		return $this->model->selectMap("id, $this->labelForForeignRelations FROM $this->table LIMIT 100");
	}

}
