<?php
/**
 * Allow developers to add PHPdoc comments to files in a semi-automated fashion.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class CodeCommentsController extends NonCachingController {

	/**
	 * If comments were submitted, add them to the appropriate file.
	 * Walk the directory looking for PHP files with uncommented sections, and show a comments form.
	 */
	public function indexAction() {

		/**
		 * If a file has the PHP extension, try showing a comments form for it.
		 * @param  $path: the path of the file to check.
		 * @return true if we need to continue looking for commentable files, or false if we can stop here.
		 */
		function walkFile($path) {
			if (substr($path, -4, 4) == '.php') {
				if (showCommentsFormIfNecessaryOrReturnFalse($path)) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Get a file's uncommented sections which can be commented, and the file source with comment markers.
		 * @param  $path: the path of the file to check for commentable sections
		 * @return an array containing [0] an array of commentable sections and [1] the file source with comment markers.
		 */
		function getCommentableSectionsAndSource($path) {
			$sections = array();
			$source = file_get_contents($path);
			while (preg_match('/([^\s\/])(\s*?[\n\r])(\t*)(|public |protected |private )(|abstract |static )(function|class)( ?)([a-zA-Z0-9_]+)([^\{]*)(\{)(.*?[\n\r]\3\})/msi', $source, $match)) {
				$replacement = $match[1] . '/**' . count($sections) . '**/' . $match[3] . $match[4] . $match[5] . $match[6] . $match[7] . $match[8] . $match[9] . $match[10] . $match[11];
				$source = str_replace($match[0], $replacement, $source);
				preg_match_all('/\$[^= \,\)]+/', $match[9], $params);
				$sections[] = array(
					'indent' => strlen($match[3]),
					'type' => $match[6],
					'name' => $match[8],
					'params' => $params[0],
					'return' => strpos($match[11], "\treturn ") ? 'mixed' : false
				);
			}
			while (preg_match('/([^\s\/])(\s*?[\n\r])(\t*)(public |protected |private )(\$)([a-zA-Z0-9_]+)/msi', $source, $match)) {
				$replacement = $match[1] . '/**' . count($sections) . '**/' . $match[3] . $match[4] . $match[5] . $match[6];
				$source = str_replace($match[0], $replacement, $source);
				$sections[] = array(
					'indent' => strlen($match[3]),
					'type' => 'property',
					'name' => $match[6]
				);
			}
			return array($sections, $source);
		}

		/**
		 * If a file has commentable sections, show a comments form.
		 * @param  $path: the file we want to show a form for.
		 * @return true if we have showed the form, or false if there were no commentable sections.
		 */
		function showCommentsFormIfNecessaryOrReturnFalse($path) {
			list($sections, $source) = getCommentableSectionsAndSource($path);
			if (count($sections)) {
				$source = highlight_string($source, true);
				foreach ($sections as $index => $section) {
					$margin = ($section['indent'] * 12) . 'px';
					$indentSpaces = $section['indent'] * 4;
					$value = "\n";

					if ($section['type'] == 'class') {
						$value .= "\n";
						if (isset($GLOBALS['AUTHOR'])) {
							$value .= "\n@author     " . $GLOBALS['AUTHOR'];
						}
						if (isset($GLOBALS['PACKAGE'])) {
							$value .= "\n@package    " . $GLOBALS['PACKAGE'];
						}
						if (isset($GLOBALS['VERSION'])) {
							$value .= "\n@since      Version " . preg_replace('/(\.[0-9]+)\.0$/', '$1', $GLOBALS['VERSION']);
						}
						if (isset($GLOBALS['COPYRIGHT'])) {
							$value .= "\n@copyright  " . $GLOBALS['COPYRIGHT'];
						}
						if (isset($GLOBALS['LICENSE'])) {
							$value .= "\n@license    " . $GLOBALS['LICENSE'];
						}
					}
					elseif ($section['type'] == 'function') {
						foreach ($section['params'] as $param) {
							$value .= "\n@param  " . $param . ": ";
						}
						if ($section['return']) {
							$value .= "\n@return ";
						}
					}
					$valueLineCount = count(explode("\n", $value));
					$fieldName = $section['name'] . '_' . $section['type'];
					$replacement = '<br><br>'
						. ($section['type'] == 'class' ? '<br>' : '')
						. str_repeat('&nbsp;', $indentSpaces)
						. '<textarea name="' . $fieldName . '" class="code" cols="' . (120 - $indentSpaces) . '" rows="' . $valueLineCount . '" style="margin:0;padding:0;">'
						. $value
						. '</textarea><br>'
						. '<input type="hidden" name="' . $fieldName . '_indent" value="' . $section['indent'] . '">';
					$source = str_replace("/**$index**/", $replacement, $source);
				}

				$GLOBALS['controller']->renderView('admin/code_comments', array(
					'title' => 'Code comments',
					'path' => $path,
					'source' => $source
				));
				return true;
			}
			return false;
		}

		// Process the form post by replacing comment markers with the posted comments.
		if (count($_POST)) {
			$path = $_POST['path'];
			list($sections, $source) = getCommentableSectionsAndSource($path);
			foreach ($sections as $index => $section) {
				$fieldName = $section['name'] . '_' . $section['type'];
				$comment = $_POST[$fieldName];
				$indent = str_repeat("\t", $_POST[$fieldName . '_indent']);
				$lines = preg_split('/\n/msi', "\n" . trim($comment));
				$comment = "\n"
					. ($section['type'] == 'class' ? '' : "\n")
					. $indent . '/**'
					. join("\n" . $indent . ' * ', $lines) . "\n"
					. $indent . ' */' . "\n"
					. ($section['type'] == 'class' ? "\n" : "");
				$comment = str_replace(" \n", "\n", $comment);
				$comment = str_replace(" \r", "\r", $comment);
				$source = str_replace("/**$index**/", $comment, $source);
				file_put_contents($path, $source);
			}
		}

		$GLOBALS['controller'] = $this;

		DirectoryUtility::walk($GLOBALS['PIE_ROOT'], 'walkFile');
	}

}
