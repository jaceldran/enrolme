<?php

/*
 * URLS y Carpetas.
 */
define ('HOME', '/enrolme');
define ('STORAGE', __DIR__.'/storage');
define ('APP', __DIR__.'/app');
define ('TEMPLATES', __DIR__.'/templates');

/*
 * Router
 */
$router['defaults'] = array(
	'type' => 'html'
	, 'template-app' => TEMPLATES . '/app-default.php'
);

$router['routes'] =& $routes;

$routes['/'] = array (
	'class' => 'App\Activity'
	, 'method' => 'viewHome'
	//, 'template-app' => TEMPLATES .'/css-test.html'
	, 'template-data' => TEMPLATES .'/css-samples.html'	
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