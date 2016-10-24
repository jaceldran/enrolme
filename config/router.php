<?php
/*
 * Router
 */
$router['defaults'] = array(
	'type' => 'html'
	, 'template-app' => TEMPLATES . '/app-default.php'
);

$router['routes'] =& $routes;

$routes['/'] = array (
	'class' => 'App\Home'
	, 'method' => 'index'	
	, 'template-data' => TEMPLATES .'/view-routes-info.html'
);

$routes['/samples'] = array(
	'class' => 'App\Activity'
	, 'method' => 'createSamples' // TODO: redirecto view/samples
);

$routes['/view/samples'] = array(
	'class' => 'App\Activity'
	, 'method' => 'viewSamples'
	, 'template-data' => TEMPLATES.'/view-activity-sample.html'
);