<?php

$MEDIA_GROUPS = array(
	'css' => array(
		'core' => array(
			'base.css',
			'scaffolds.css',
			'veil.css'
		)
	),
	'js' => array(
		'core' => array(
			$ENVIRONMENT == 'development' ? 'jquery-1.4.2.min.js' : 'jquery-1.4.2.min.js',
			'base.js',
			'googleAds.js',
			'googleAnalytics.js',
			'facebook.js',
			'uservoice.js'
		)
	)
);
