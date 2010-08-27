<?php

class ErrorsController extends Controller {

	public $errorCount = 0;

	public $uniqueErrors = array();

	function indexAction($errorCode = '500') {
		$this->renderError($errorCode);
	}

	function processError($errorCode) {
		$this->renderView('errors/error_' . $errorCode, array('title' => $errorCode . ' Error'));
	}

	function fatalAction() {
		$error = trim($_REQUEST['error']);
		if (preg_match('/^(Fatal|Parse) error: (.*) in (.*) on line (.*)$/', $error, $match)) {
			$this->handleError($match[1], $match[2], $match[3], $match[4], NULL, false);
			$this->renderRefresher();
		}
		else {
			die($error);
		}
	}

	function renderSourceCode($file, $lineNumber, $className = '') {
		$source = show_source($file, true);
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

	function countErrorAndReturnStats($concatenatedErrorInfo) {
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

	function handleError($level, $message, $file, $lineNumber, $context = NULL, $showStackTrace = true) {
		global $HTTP_ROOT;

		$errorStats = $this->countErrorAndReturnStats($level . $message . $file . $lineNumber);
		if ($errorStats['firstOfItsKind']) {
			$file = str_replace('\\', '/', $file);
			?>
			</var></form>
			<form class="code" method="post" action="<?php echo $HTTP_ROOT; ?>errors/rewrite">
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
				<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
				<script type="text/javascript" src="/media/base.js"></script>
				<script type="text/javascript" src="/media/error_handler.js"></script>
				<?php
			}
		}
	}

	function renderPath($path) {
		global $APP_ROOT, $PIE_ROOT;
		if (strpos($path, $APP_ROOT) === 0) {
			$path = str_replace($APP_ROOT, '<i title="' . $APP_ROOT . '">APP_ROOT.</i>', $path);
		}
		else if (strpos($path, $PIE_ROOT) === 0) {
			$path = str_replace($PIE_ROOT, '<i title="' . $PIE_ROOT . '">PIE_ROOT.</i>', $path);
		}
		echo $path;
	}

	function renderValue($value) {
		//TODO: make a fancier display for function argument values and context variable values.
		if (is_string($value)) {
			echo "'" . addslashes($value) . "'";
		}
		else {
			print_r($value);
		}
	}

	function rewriteAction() {
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
	}

}
