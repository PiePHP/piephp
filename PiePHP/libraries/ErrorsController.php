<?php
/**
 * Handle errors within a page and display pages for common error codes.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class ErrorsController extends Controller {

	/**
	 * The number of errors that have been handled in the page.
	 */
	public $errorCount = 0;

	/**
	 * A hash of errors, keyed for uniqueness by level, message, file and line.
	 */
	public $uniqueErrors = array();

	/**
	 * Display a server error page.
	 */
	public function indexAction() {
		$this->processError(500);
	}

	/**
	 * Display an error page.
	 * @param  $errorCode: the HTTP error code that we want to display a page for.
	 */
	public function catchAllAction($errorCode = 500) {
		$this->processError($errorCode);
	}

	/**
	 * Do what needs to be done when a specified error has occurred.
	 * @param  $errorCode:
	 */
	public function processError($errorCode) {
		$this->renderView('errors/error_' . $errorCode, array('title' => $errorCode . ' Error'));
	}

	/**
	 * Handle a fatal error that has sent its error message back to the server via error prepend and append.
	 */
	public function fatalAction() {
		$error = trim($_REQUEST['error']);
		if (preg_match('/^(Fatal|Parse) error: (.*) in (.*) on line (.*)$/', $error, $match)) {
			$this->handleError($match[1], $match[2], $match[3], $match[4], NULL, false);
		}
		else {
			die($error);
		}
	}

	/**
	 * Show color-coded PHP which can become editable on hover.
	 * @param  $file: the path of the file we want to show.
	 * @param  $lineNumber: the line number to highlight.
	 * @param  $className: the CSS class for the block that will display the code.
	 */
	public function renderSourceCode($file, $lineNumber, $className = '') {
		$source = highlight_file($file, true);
		preg_match('/<span([^>]+)>/', $source, $match);
		$span = $match[0];
		$source = trim(preg_replace('/(^<code>\\s*<span[^>]*>|<\/span>\\s*<\/code>$)/', '', $source));
		$lines = explode('<br />', $source);
		$newLines = array();
		$pad = strlen('' . count($lines));
		foreach ($lines as $i => $line) {
			$line = trim($line);
			if (preg_match('/<span([^>]+)>[^<]*$/', $line, $match)) {
				$span = '<span' . $match[1] . '>';
			}
			$newLines[] = '<code' . ($i == $lineNumber - 1 ? ' class="line"' : '') . '>'
				. '<i>' . sprintf('%0' . $pad . 's', $i + 1) . '&nbsp;</i>'
				. (strpos($line, '<span') === 0 ? '' : $span) . $line . '</span>'
				. '</code>';
		}
		$source = join("\n", $newLines);
		?>
		<input type="hidden" value="<?php echo htmlentities($file); ?>">
		<textarea style="padding-left:<?php echo ($pad * 1) * 7; ?>px"></textarea>
		<blockquote class="<?php echo $className; ?>"><?php echo $source; ?></blockquote>
		<?php
	}

	/**
	 * Track a specific error so we can know if it's the first one in the page or the first of its kind.
	 * @param  $concatenatedErrorInfo:
	 * @return
	 */
	public function countErrorAndReturnStats($concatenatedErrorInfo) {
		$this->errorCount++;
		if (isset($this->uniqueErrors[$concatenatedErrorInfo])) {
			++$this->uniqueErrors[$concatenatedErrorInfo];
			$firstOfItsKind = false;
		}
		else {
			$this->uniqueErrors[$concatenatedErrorInfo] = 1;
			$firstOfItsKind = true;
		}
		return array(
			'firstInPage' => $this->errorCount == 1,
			'firstOfItsKind' => $firstOfItsKind
		);
	}

	/**
	 * Handle an error that has been triggered without exception handling.
	 * @param  $level: the PHP error level.
	 * @param  $message: the PHP error message for the error that is being handled.
	 * @param  $file: the file in which the error occurred.
	 * @param  $lineNumber: the line on which the error occurred.
	 * @param  $context: the variables that were in the local scope when the error occurred.
	 * @param  $showStackTrace: the stack trace at the point where the error occurred.
	 */
	public function handleError($level, $message, $file, $lineNumber, $context = NULL, $showStackTrace = true) {
		global $HTTP_ROOT;

		Logger::error("$message in $file on line $lineNumber");

		$errorStats = $this->countErrorAndReturnStats($level . $message . $file . $lineNumber);
		if ($errorStats['firstOfItsKind']) {
			$file = str_replace('\\', '/', $file);

			// Try ending some tags because we're inserting HTML into an unknown point in the page.
			// Echoing a string means that Eclipse won't complain about unmatched tags.
			echo '</var></form>';

			?>
			<form class="code" method="post" action="<?php echo $HTTP_ROOT; ?>errors/rewrite" target="submitter">
				<h3><?php echo $message; ?></h3>
				<h4><br><?php $this->renderPath($file); ?><i>, line</i> <?php echo $lineNumber; ?></h4>
				<?php
				$this->renderSourceCode($file, $lineNumber, 'error');
				if ($showStackTrace) {
					$trace = debug_backtrace();
					array_shift($trace);
					array_shift($trace);
					?>
					<h4><br>Stack trace</h4>
					<?php
					$file = '';
					$lineNumber = '';
					$function = '';
					$arguments = '';
					foreach ($trace as $stackDepth => $call) {
						if ($stackDepth > 99) {
							break;
						}
						if (isset($call['file'])) {
							$file = str_replace('\\', '/', $call['file']);
						}
						if (isset($call['line'])) {
							$lineNumber = $call['line'];
						}
						else {
							$lines = file($file);
							foreach ($lines as $lineIndex => $line) {
								if (preg_match('/function ' . $call['function'] . '\(/', $line)) {
									$lineNumber = $lineIndex + 1;
									break;
								}
							}
						}
						if (isset($call['function'])) {
							$function = $call['function'];
							if ($function == 'trigger_error' || $function == 'triggerError') {
								$context = array();
								continue;
							}
							if (isset($call['class']) && isset($call['type'])) {
								$function = $call['class'] . $call['type'] . $function;
							}
						}
						if (isset($call['args'])) {
							$arguments = $call['args'];
						}
						else {
							$arguments = array();
						}

						echo '<div class="call toggle">';
						$this->renderPath($file);
						echo '<i>, line</i> ' . $lineNumber . ': <u>' . $function . '(';
						foreach ($arguments as $j => $argument) {
							if ($j) {
								echo ', ';
							}
							$this->renderValue($argument);
						}
						echo ')</u></div>';
						$this->renderSourceCode($file, $lineNumber, 'trace');
					}
				}

				if (count($context)) {
					?>
					<br>
					<h4>Context</h4>
					<?php
					foreach ($context as $variable => $value) {
						echo '<div>' . $variable . ': <u>';
						$this->renderValue($value);
						echo '</u></div>';
					}
				}
				?>
			</form>
			<?php
			if ($errorStats['firstInPage']) {
				?>
				<link rel="stylesheet" href="/media/base.css" type="text/css">
				<script type="text/javascript" src="/media/js/jquery-1.4.2.js"></script>
				<script type="text/javascript" src="/media/js/base.js"></script>
				<script type="text/javascript" src="/media/js/error_handler.js"></script>
				<?php
			}
		}
	}

	/**
	 * Get HTML for a stack trace entry with APP_ROOT and PIE_ROOT replaced.
	 * @param  $path: the file path for the stack trace entry.
	 */
	public function renderPath($path) {
		global $APP_ROOT, $PIE_ROOT;
		if (strpos($path, $APP_ROOT) === 0) {
			$path = str_replace($APP_ROOT, '<i title="' . $APP_ROOT . '">APP_ROOT.</i>', $path);
		}
		else if (strpos($path, $PIE_ROOT) === 0) {
			$path = str_replace($PIE_ROOT, '<i title="' . $PIE_ROOT . '">PIE_ROOT.</i>', $path);
		}
		echo $path;
	}

	/**
	 * Show the value of a variable in a stack trace or the last context.
	 * @param  $value: the value to be shown.
	 */
	public function renderValue($value) {
		//TODO: Make a fancier display for function argument values and context variable values.
		if (is_string($value)) {
			echo "'" . addslashes($value) . "'";
		}
		else {
			print_r($value);
		}
	}

	/**
	 * Rewrite a file using text that was posted through the error handler.
	 */
	public function rewriteAction() {
		global $APP_ROOT;
		$file = $_REQUEST['file'];
		$code = '';
		$possibleEncodings = array("UTF-8", "ASCII", "Windows-1252", "ISO-8859-15", "ISO-8859-1", "ISO-8859-6", "CP1256");
		foreach ($possibleEncodings as $encoding) {
			$converted = iconv($encoding, 'ASCII//IGNORE//TRANSLIT', $_REQUEST['code']);
			if (strlen($converted) > strlen($code)) {
				$code = $converted;
			}
		}
		$code = str_replace('  ' . '  ', '	', $code);
		file_put_contents(str_replace('.php', '.backup.php', $file), file_get_contents($file));
		file_put_contents($file, $code);
		if (isset($GLOBALS['REFRESHER_FILE'])) {
			touch($GLOBALS['REFRESHER_FILE']);
		}
		echo '<script>parent.location = "" + parent.location.href;</script>';
		exit;
	}

}
