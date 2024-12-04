<?php 
	$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	if ($requestUri == '/home') {
		require 'home.php';
	}

?>