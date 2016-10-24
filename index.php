<?php namespace App;

/*
 * Require básico.
 */
require 'config/all.php';
require 'vendor/Zentric/Helpers.php';


/*
 * App Start
 */
$settings['router'] = $router;
$settings['locale'] = $locale; 
$app = new \Zentric\App($settings);
$app->start();
