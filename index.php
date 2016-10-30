<?php namespace App;

/*
 * Require básicos: config y helpers
 */
require 'config/all.php';
require 'vendor/zentric/Helpers.php';

/*
 * App Start
 */
$settings['router'] = $router;
$settings['locale'] = $locale; 
$app = new \Zentric\App($settings);
$app->start();
