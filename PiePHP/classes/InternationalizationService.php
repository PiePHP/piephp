<?php
/**
 * Facilitate interfaces in multiple languages.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class InternationalizationService {

	/**
	 * "Say" a phrase in the target language, or in the base language if we don't have a translation for it.
	 * TODO: Actually implement multiple languages.
	 * @param  $phrase: The phrase in the base language.
	 * @param  $contextOrSubstitutions: The context hints at the usage. e.g. say('Weak', 'password strength').
	 * @param  $substitutions: The substitutions are values to replace placeholders. e.g. say('Hello {0}!', array('Sam')).
	 * @return the phrase in the target language.
	 */
	public function say($phrase, $contextOrSubstitutions = '', $substitutions = array()) {
		if (is_array($contextOrSubstitutions)) {
			$substitutions = $contextOrSubstitutions;
			$context = '';
		}
		else {
			$context = $contextOrSubstitutions;
		}
		foreach ($substitutions as $index => $substitution) {
			$phrase = str_replace('{' . $index . '}', $substitution, $phrase);
		}
		return $phrase;
	}

}