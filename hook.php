<?php

$config['allowedIps'] = array();
$config['repos'] = array(
	'myRepo' => array(
		'branch' => 'branchLocation'
		),
	'anotherRepo' => array(
		'branch' => 'branchLocation'
		)
	);

if (!in_array($ip, $config['allowedIps'])) {
	header('HTTP/1.0 404 Not Found');
	die();	
}
if (!isset($_GET['repo'])) {
	die();
}

$repo = $config[$_GET['repo']];

