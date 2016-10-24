<?php

/*
 * Archivos de configuración.
 */
require 'paths.php';
require 'router.php';


// ---------------------
// Opciones de autoload.
// ---------------------


/*
 * Autoload "manual.
 * Si no se usa composer, descomentar este bloque.
 */
/*spl_autoload_register(function($class) {
	$folders = array(
		'App' => __DIR__ . '/app', 
		'Zentric' => 'vendor/zentric'
	);	
    $pos = strrpos($class, '\\');
	$classname = substr($class, $pos + 1);
	$namespace = substr($class,0,$pos);
	$folder = $folders[$namespace];
	$file = "$folder/$classname.php";
	if (file_exists($file)) {
		include_once $file;
	} else {
		trigger_error ("No encuentra ($class) $file.<br>");
	}
});*/

/*
 * Autoload "composer"
 * Si no se usa composer, comentar esta línea. 
 */
require 'vendor/autoload.php'; 