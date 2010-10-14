/**
 * Point special iFrames to the GoogleAdsController.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

wire(function(query) {
	query.find('#mrec,#sky').each(function(i, iframe) {
		setTimeout(function() {
			var clientAndSlot = iframe.className.split('/');
			var iframeDocument = (iframe.contentWindow || iframe.contentDocument).document;
			iframeDocument.open();
			iframeDocument.write('<html>'
				+ '<head><title>' + $('h1:first').text() + '</title></head>'
				+ '<body style="margin:0;padding:0">'
				+ '<script type="text/javascript">'
				+ 'google_ad_client = "pub-' + clientAndSlot[0] + '";'
				+ 'google_ad_slot = "' + clientAndSlot[1] + '";'
				+ 'google_ad_width = ' + $(iframe).width() + ';'
				+ 'google_ad_height = ' + $(iframe).height() + ';'
				+ '</script>'
				+ '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
				+ '<div style="display:none">' + $('#body').html() + '</div>'
				+ '</body>'
				+ '</html>');
			iframeDocument.close();
		}, 500);
	});
});