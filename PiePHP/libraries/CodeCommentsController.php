<?php

class CodeCommentsController extends Controller {

	public $isCacheable = false;

	function indexAction() {

		function show($path) {
			if (substr($path, -4, 4) == '.php') {
				if (showCommentsFormIfNecessaryOrReturnFalse($path)) {
					return false;
				}
			}
			return true;
		}

		function showCommentsFormIfNecessaryOrReturnFalse($path) {
			$sections = array();
			$source = file_get_contents($path);
			while (preg_match('/([^\s\/])(\s*?[\n\r])(\t*)(|public |protected |private )(function|class)( ?)([a-zA-Z0-9_]+)([^\{]*)(\{)(.*?[\n\r]\3\})/msi', $source, $match)) {
				$replacement = $match[1] . '/**' . count($sections) . '**/'. $match[3] . $match[4] . $match[5] . $match[6] . $match[7] . $match[8] . $match[9] . $match[10];
				$source = str_replace($match[0], $replacement, $source);
				preg_match_all('/\$[^= ]+/', $match[8], $params);
				$sections[] = array(
					'indent' => strlen($match[3]),
					'type' => $match[5],
					'name' => $match[7],
					'params' => $params[0],
					'return' => strpos($match[10], "\treturn ") ? 'mixed' : false
				);
			}
			if (count($sections)) {
				$source = highlight_string($source, true);
				while (preg_match('/\/\*\*([0-9]+)\*\*\//msi', $source, $match)) {
					$section = $sections[$match[1]];
					$margin = ($section['indent'] * 12) . 'px';
					$indentSpaces = $section['indent'] * 4;
					$value = "\n\n";

					if ($section['type'] == 'class') {
						$value .= "\n";
						if (isset($GLOBALS['AUTHOR'])) {
							$value .= "@author     " . $GLOBALS['AUTHOR'] ."\n";
						}
						if (isset($GLOBALS['PACKAGE'])) {
							$value .= "@package    " . $GLOBALS['PACKAGE'] ."\n";
						}
						if (isset($GLOBALS['VERSION'])) {
							$value .= "@since      Version " . preg_replace('/(\.[0-9]+)\.0$/', '$1', $GLOBALS['VERSION']) ."\n";
						}
						if (isset($GLOBALS['COPYRIGHT'])) {
							$value .= "@copyright  " . $GLOBALS['COPYRIGHT'] ."\n";
						}
						if (isset($GLOBALS['LICENSE'])) {
							$value .= "@license    " . $GLOBALS['LICENSE'] ."\n";
						}
					}
					elseif ($section['type'] == 'function') {
						foreach ($section['params'] as $param) {
							$value .= "@param  " . $param ." - \n";
						}
						if ($section['return']) {
							$value .= "@return \n";
						}
					}
					$valueLineCount = count(explode("\n", $value));
					$replacement = '<br>'
						. str_repeat('&nbsp;', $indentSpaces)
						. '<textarea class="code" cols="' . (120 - $indentSpaces) . '" rows="' . ($valueLineCount) . '">'
						. $value
						. '</textarea><br>';
					if ($section['type'] == 'class') {
					}
					else {
						$replacement = '<br>' . $replacement;
					}
					$source = str_replace($match[0], $replacement, $source);
				}
				echo $source;
				return true;
			}
			return false;
		}

		DirectoryUtility::walk($GLOBALS['PIE_ROOT'], 'show');
	}

}
