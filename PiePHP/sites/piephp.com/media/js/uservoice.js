/**
 * Set UserVoice options and import their JavaScript.
 * This displays the "feedback" side tab and ultimately facilitates gathering feature ideas.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2007-2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

var uservoiceOptions = {
	key: 'piephp',
	host: 'piephp.uservoice.com', 
	forum: 'general', 
	alignment: 'right',
	background_color:'#123', 
	text_color: 'white',
	hover_color: '#012',
	lang: 'en',
	showTab: true
};

// The script is appended to the DOM, so it will load asynchronously in most browsers.
appendScript(document.location.protocol + '//cdn.uservoice.com/javascripts/widgets/tab.js');