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
	query.find('iframe.gAd').each(function(i, iframe) {
		setTimeout(function() {
			iframe.src = baseUrl + '/google_ads/' + iframe.title + '/' + $(iframe).width() + '/' + $(iframe).height();
			iframe.title = '';
		}, 500);
	});
});