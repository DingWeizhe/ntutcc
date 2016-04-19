<?php
	$config['facebook'] = array(
		'app' => array(
			'appId' => '',
			'secret' => '',
			'fileUpload' => false,
			'allowSignedRequest' => false,
			'cookie' => false
		),
		'login' => array(
			'scope' => 'read_stream, friends_likes',
			'redirect_uri' => 'http://' . HOST . '/user/login/'
		),
		'logout' => array( 'next' => 'http://' . HOST . '/user/' )
	);
?>