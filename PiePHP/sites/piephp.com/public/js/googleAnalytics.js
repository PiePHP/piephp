/**
 * Use Google Analytics for tracking pageviews and events.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

var statsCategory;

window._gaq = [['_setAccount', 'UA-18302817-1'], ['_trackPageview']];

// The script is appended to the DOM, so it will load asynchronously in most browsers.
appendScript(('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js');


/**
 * Track a pageview, and optionally use a URL that is not the current page's URL (like we do for /player/play)
 */
var trackPageview = function(url) {
	_gaq.push(['_trackPageView', url]);
	statsCategory = url;
};

/**
 * Track an event.
 */
var trackEvent = function(action, label, value, category) {
	label = label ? label : '';
	value = value ? value : time() - startTime;
	category = category ? category : statsCategory;
	_gaq.push(['_trackEvent', category, action, label, value]);
};