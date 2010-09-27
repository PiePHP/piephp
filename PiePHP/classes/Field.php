<?php
/**
 * Subclasses of Field are used to display and edit data within Scaffolds.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class Field {

	/**
	 * The name of the field is used in form inputs.
	 */
	public $name = '';

	/**
	 * The field's column name is used in Models.
	 */
	public $column = '';

	/**
	 * The field type is used by scaffolds to instantiate its subclass.
	 * TODO: Determine whether it's necessary for this to be a property.
	 */
	public $type = 'Field';

	/**
	 * If a field is required, its value must be non-empty.
	 * Other validation rules may apply.
	 */
	public $required = false;

	/**
	 * Minimum length of the submitted value and corresponding database field.
	 */
	public $minlength = 0;

	/**
	 * Maximum length of the submitted value and corresponding database field.
	 */
	public $maxlength = 0; // Zero indicates no limit.

	/**
	 * CSS class for the field.
	 */
	public $cssClass = '';

	/**
	 * User-visible label for the field, which is displayed in tables and forms.
	 */
	public $label = '';

	/**
	 * Value that was posted or retrieved from the scaffold Model.
	 */
	public $value = '';

	/**
	 * User-visible hint which appears greyed-out in the field until it gets focused.
	 */
	public $hint = '';

	/**
	 * Advice message which is shown in relation to validation errors.
	 */
	public $advice = '';

	/**
	 * Upon validation, we can track whether the field data has errors.
	 */
	public $hasValidationErrors = false;

	/**
	 * Initialize the field by setting its properties from an array.
	 * @param  $settings: the associative array of settings.
	 * @param  $scaffold: the scaffold to which this field belongs.
	 */
	public function __construct($settings, $scaffold = NULL) {
		foreach ($settings as $settingName => $value) {
			$this->$settingName = $value;
		}
		if (!$this->column) {
			$this->column = separate($this->name, '_');
		}
		$this->scaffold = $scaffold;
		$this->initialize();
	}

	/**
	 * Some types of fields will need additional initialization.
	 */
	public function initialize() {
	}

	/**
	 * Render this field's section in a form.
	 */
	public function renderFormField() {
		echo '<div>';
			echo '<label>' . $this->getLabel() . '</label>';
			$this->renderInput();
			$this->renderAdvice();
			$this->renderTip();
		echo '</div>';
	}

	/**
	 * Get the user-visible label describing this field.
	 * @return the label as a string.
	 */
	public function getLabel() {
		return $this->label ? $this->label : ucfirst(separate($this->name, ' '));
	}

	/**
	 * Render the form input.
	 * The input can be overridden to be a textarea, select, radios, checkboxes, etc.
	 */
	public function renderInput() {
		echo '<input type="' . $this->type . '" name="' . $this->name . '"';
		if ($this->maxlength) {
			echo ' maxlength="' . $this->maxlength . '"';
		}
		$this->renderInputClass();
		echo ' value="' . htmlentities($this->type == 'password' ? '' : $this->getValue()) . '"';
		if ($this->hint) {
			echo ' title="' . htmlentities($this->hint) . '"';
		}
		echo '>';
	}

	/**
	 * Get the value of this field from POST data or from the Model.
	 * @return the POSTed or stored value.
	 */
	public function getValue() {
		if (isset($_POST[$this->name])) {
			return $_POST[$this->name];
		}
		if (isset($this->scaffold->result[$this->column])) {
			return $this->scaffold->result[$this->column];
		}
		return '';
	}

	/**
	 * Set the value of this field on the scaffold for processing in the Model.
	 */
	public function setColumnValueOnScaffold() {
		$this->scaffold->columnValues[$this->column] = $this->getValue();
	}

	/**
	 * Render the CSS class for this field.
	 * TODO: This might be more straightforward if it returned a value.
	 */
	public function renderInputClass() {
		$className = $this->cssClass ? $this->cssClass : lower_camel($this->type);
		if ($this->required) {
			$className = 'required ' . $className;
		}
		if ($className) {
			echo ' class="' . $className . '"';
		}
	}

	/**
	 * Render a tip message that explains the usage of this method.
	 */
	public function renderTip() {
	}

	/**
	 * Render advice regarding accepted values, which can be shown following validation.
	 */
	public function renderAdvice() {
		$className = 'advice';
		if ($this->hasValidationErrors) {
			$className .= ' error';
		}
		echo '<div class="' . $className . '">';
		echo $this->getAdvice();
		echo '</div>';
	}

	/**
	 * Get the advice message, which can be shown following validation.
	 * @return the advice message as a string.
	 */
	public function getAdvice() {
		return $this->advice ? $this->advice : $this->getDefaultAdvice();
	}

	/**
	 * Get a generic validation advice message.
	 * @return the advice message as a string.
	 */
	public function getDefaultAdvice() {
		$advice = 'Please enter a valid ' . separate($this->type, ' ') . '.';
		if ($this->minlength) {
			$advice .= '<br>It must be at least ' . $this->minlength . ' characters long.';
		}
		return $advice;
	}

	/**
	 * Render the heading that displays for this field when shown in a data table.
	 */
	public function renderListHeading() {
		echo '<th>';
		echo $this->getLabel();
		echo '</th>';
	}

	/**
	 * Render a cell containing a value for this field in a data table.
	 * @param  $isFirst: whether this is the first cell in its row.
	 */
	public function renderListCell($isFirst = false) {
		echo '<td>';
		if ($isFirst) {
			echo '<a href="' . $this->scaffold->urlRoot . 'change/' . $this->scaffold->result['id'] . '">';
		}
		$this->renderListCellValue();
		if ($isFirst) {
			echo '</a>';
		}
		echo '</td>';
	}

	/**
	 * Render the value for this field in a data table.
	 */
	public function renderListCellValue() {
		$value = $this->scaffold->result[$this->column];
		echo htmlentities($value);
	}

	/**
	 * Validate this field and record the presence of any validation errors against the field and the scaffold.
	 */
	public function validate() {
		if ($this->isValid()) {
			$this->hasValidationErrors = false;
		}
		else {
			$this->hasValidationErrors = true;
			$this->scaffold->hasValidationErrors = true;
		}
	}

	/**
	 * Check the field for validity.
	 * @return true if the field is valid.
	 */
	public function isValid() {
		return !$this->required || !$this->isEmpty();
	}

	/**
	 * Check the field for emptiness.
	 * @return true if the field is empty.
	 */
	public function isEmpty() {
		return !$this->getValue();
	}

	/**
	 * Do any necessary data processing before the scaffold's data is processed.
	 */
	public function processBeforeScaffold() {
	}

	/**
	 * Do any necessary data processing after the scaffold's data is processed.
	 */
	public function processAfterScaffold() {
	}
}