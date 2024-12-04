<?php 
	$url = $_SERVER['REQUEST_URI'];


	if ($url == '/home') {
		require 'home.php';
	}

?>