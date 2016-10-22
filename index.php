<?php namespace App;

/*
 * Ini.
 */
require 'config.php';
require APP . '/Helpers.php';

/*
 * Autoload.
 */
spl_autoload_register(function($class) {
	$path = APP . "/$class.php";
	$path = str_replace('App\\','',$path);
	if (file_exists($path)) {
		include_once $path;
	} else {
		trigger_error ("no encuentra $path");
	}	
});

/*
 * App.
 */
$app = new App($router);
$app->start();
