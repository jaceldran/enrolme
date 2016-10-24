<?php namespace App;

/*
 * Require básico.
 */
require 'config/all.php';
require 'vendor/Zentric/Helpers.php';


/*
 * App Start
 */
$app = new \Zentric\App($router);
$app->start();
