/**
 * Connect to Google Analytics, and track a pageview.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

// TODO: Record pageviews when AJAX is used to get pages!!!
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-18302817-1']);
_gaq.push(['_trackPageview']);

// The script is appended to the DOM, so it will load asynchronously in most browsers.
appendScript(('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js');