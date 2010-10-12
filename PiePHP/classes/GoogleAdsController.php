<?php
/**
 * A user interface that will allow scaffolds to be created from a browser.
 *
 * @author     Sam Eubank <sam@piephp.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2010, Pie Software Foundation
 * @license    http://www.piephp.com/license
 */

class GoogleAdsController extends Controller {

	/**
	 * Show an ad.
	 */
	public function catchAllAction($client, $slot, $width, $height) {
		?>
		<html>
		<head>
			<title>Ad</title>
		</head>
		<body style="margin:0;padding:0">
			<script type="text/javascript">
			google_ad_client = 'pub-<?php echo $client; ?>';
			google_ad_slot = '<?php echo $slot; ?>';
			google_ad_width = <?php echo $width; ?>;
			google_ad_height = <?php echo $height; ?>;
			</script>
			<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
		</body>
		</html>
		<?php
		exit;
	}
}
