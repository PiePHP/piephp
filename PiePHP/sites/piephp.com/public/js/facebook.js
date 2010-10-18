/**
 * Allow us to put Facebook like controls and other such stuff into pages.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

$('body').append('<div id="fb-root"/>');

window.fbAsyncInit = function() {
	var init = function() {
		try {
			FB.init({appId: 'your app id', status: true, cookie: true, xfbml: true});
		}
		catch (e) {
			// Naughty naughty, Facebook.  Clean up your JavaScript.
		}
	};
	init();
	wire(init);
}

// The script is appended to the DOM, so it will load asynchronously in most browsers.
// TODO: turn this back on once we have a Facebook app ID.
appendScript(document.location.protocol + '//connect.facebook.net/en_US/all.js', 500);
