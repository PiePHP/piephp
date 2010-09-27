/**
 * Set UserVoice options and import their JavaScript.
 * This displays the "feedback" side tab and ultimately facilitates gathering feature ideas.
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
		FB.init({appId: 'your app id', status: true, cookie: true, xfbml: true});
	};
	init();
	wire(init);
}

// The script is appended to the DOM, so it will load asynchronously in most browsers.
appendScript(document.location.protocol + '//connect.facebook.net/en_US/all.js', 500);