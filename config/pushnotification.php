<?php

return [
	'gcm' => [
		'priority' => 'normal',
		'dry_run'  => env('ANDROID_ENVIRONMENT') != 'production',
		'apiKey'   => env('ANDROID_APIKEY'),
	],
	'fcm' => [
		'priority' => 'normal',
		'dry_run'  => false,
		'apiKey'   => 'My_ApiKey-Ly4tM',
	],
	'apn' => [
		'certificate' => env('IOS_CERTIFICATE'),
		'passPhrase'  => env('IOS_PASSPHRASE'),
		'passFile'    => '', // optional
		'dry_run'     => env('IOS_ENVIRONMENT') != 'production',
	]
];
