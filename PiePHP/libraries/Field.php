<?php

class Field {

	public $name = '';

	public $column = '';

	public $type = 'Field';

	public $required = false;

	public $minlength = 0;

	public $maxlength = 0; // Zero indicates no limit.

	public $label = '';

	public $value = '';

	public $hint = '';

	public $advice = '';

	public $hasValidationErrors = false;

	function __construct($settings, $scaffold = NULL) {
		foreach ($settings as $settingName => $value) {
			$this->$settingName = $value;
		}
		if (!$this->column) {
			$this->column = separate($this->name, '_');
		}
		$this->scaffold = $scaffold;
	}

	function renderFormField() {
		echo '<div>';
			echo '<label>' . $this->getLabel() . '</label>';
			$this->renderInput();
			$this->renderAdvice();
			$this->renderTip();
		echo '</div>';
	}

	function getLabel() {
		return $this->label ? $this->label : ucfirst(separate($this->name, ' '));
	}

	function renderInput() {
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

	function getValue() {
		if (isset($_POST[$this->column])) {
			return $_POST[$this->column];
		}
		if (isset($this->scaffold->result[$this->column])) {
			return $this->scaffold->result[$this->column];
		}
		return '';
	}

	function setColumnValueOnScaffold() {
		$this->scaffold->columnValues[$this->column] = $this->getValue();
	}

	function renderInputClass() {
		$className = lower_camel($this->type);
		if ($this->required) {
			$className = 'required ' . $className;
		}
		echo ' class="' . $className . '"';
	}

	function renderTip() {
	}

	function renderAdvice() {
		$className = 'advice';
		if ($this->hasValidationErrors) {
			$className .= ' error';
		}
		echo '<div class="' . $className . '">';
		echo $this->getAdvice();
		echo '</div>';
	}

	function getAdvice() {
		return $this->advice ? $this->advice : $this->getDefaultAdvice();
	}

	function getDefaultAdvice() {
		$advice = 'Please enter a valid ' . separate($this->type, ' ') . '.';
		if ($this->minlength) {
			$advice .= '<br>It must be at least ' . $this->minlength . ' characters long.';
		}
		return $advice;
	}

	function renderListHeading() {
		echo '<th>';
		echo $this->getLabel();
		echo '</th>';
	}

	function renderListCell($isFirst = false) {
		echo '<td>';
		if ($isFirst) {
			echo '<a href="' . $this->scaffold->path . 'change/' . $this->scaffold->result['id'] . '">';
		}
		$this->renderListCellValue();
		if ($isFirst) {
			echo '</a>';
		}
		echo '</td>';
	}

	function renderListCellValue() {
		$value = $this->scaffold->result[$this->column];
		echo htmlentities($value);
	}

	function validate() {
		if ($this->isValid()) {
			$this->hasValidationErrors = false;
		}
		else {
			$this->hasValidationErrors = true;
			$this->scaffold->hasValidationErrors = true;
		}
	}

	function isValid() {
		return !$this->required || !$this->isEmpty();
	}

	function isEmpty() {
		return !$this->getValue();
	}

	function processBeforeScaffold() {
	}

	function processAfterScaffold() {
	}
}