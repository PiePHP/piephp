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
			'jquery-1.4.4' . ($ENVIRONMENT == 'development' ? '.min' : '') . '.js',
			'base.js',
			'googleAds.js',
			'googleAnalytics.js',
			'facebook.js',
			'uservoice.js',
			'wireDocument.js'
		),
		'errors' => array(
			'errors.js'
		)
	)
);
