<?php

$config['allowedIps'] = array();
$config['repos'] = array(
	'myRepo' => array(
		'branch' => 'branchLocation'
		)
	);

/* Utility to get IP */
function getIp() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		return $_SERVER['REMOTE_ADDR'];
	}
}


/* Function from: http://github.com/kwangchin/GitHubHook */
function ip_in_cidrs($ip, $cidrs) {
	$ipu = explode('.', $ip);

	foreach ($ipu as &$v) {
		$v = str_pad(decbin($v), 8, '0', STR_PAD_LEFT);
	}

	$ipu = join('', $ipu);
	$result = FALSE;

	foreach ($cidrs as $cidr) {
		$parts = explode('/', $cidr);
		$ipc = explode('.', $parts[0]);

		foreach ($ipc as &$v) $v = str_pad(decbin($v), 8, '0', STR_PAD_LEFT); {
			$ipc = substr(join('', $ipc), 0, $parts[1]);
			$ipux = substr($ipu, 0, $parts[1]);
			$result = ($ipc === $ipux);				
		}

		if ($result) break;
	}

	return $result;
}

if (!ip_in_cidrs(getIP(), $config['allowedIps'])) {
	header('HTTP/1.0 404 Not Found');
	die();
}
if (!isset($_GET['repo'])) {
	die();
}

$repo = $config[$_GET['repo']];

if (isset($_POST['payload'])) {
	$payload = json_decode($_POST['payload']);
} else {
	die();
}

foreach ($repo as $branch => $src) {
	if ($payload->ref == 'refs/head/'.$branch) {
		exec('cd '.$src.';git pull origin '.$branch);
	}
}