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
	query.find('iframe.mrec,iframe.sky').each(function(iframeIndex, iframe) {
		setTimeout(function() {
			var classNames = iframe.className.split(' ');
			var iframeQuery = $(iframe).attr('frameborder', 0).attr('scrolling', 'no');
			var iframeDocument = (iframe.contentWindow || iframe.contentDocument).document;
			iframeDocument.open();
			iframeDocument.write('<html>'
				+ '<head><title>' + $('h1:first').text() + '</title></head>'
				+ '<body style="margin:0;padding:0">'
				+ '<div style="display:none">' + $('#body').html().replace(/<[^>]+>/, ' ') + '</div>'
				+ '<script type="text/javascript">'
				+ 'google_ad_client = "pub-' + classNames[1] + '";'
				+ 'google_ad_slot = "' + classNames[2] + '";'
				+ 'google_ad_width = ' + iframeQuery.width() + ';'
				+ 'google_ad_height = ' + iframeQuery.height() + ';'
				+ '</script>'
				+ '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
				+ '</body>'
				+ '</html>');
			iframeDocument.close();
		}, 500);
	});
});