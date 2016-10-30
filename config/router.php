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
	'class' => 'App\Page'
	, 'method' => 'home'
);

// página de inscripción a la actividad :uid
$routes['/in/:uid'] = array ( // enrol me in/xxx...
	'class' => 'App\Page'
	, 'method' => 'enrolme'
	, 'javascript' => '<script src="'.HOME.'/js/enrolment.js"></script>'
);

// listado de participantes de la actividad :uid
$routes['/enrollers/:uid'] = array (
	'class' => 'App\Page'
	, 'method' => 'enrollers'
	//, 'javascript' => '<script src="'.HOME.'/js/enrolment.js"></script>'
);

$routes['/style'] = array ( // muestras de style
	'class' => 'App\Style'
	, 'method' => 'sample'	
	, 'template-data' => TEMPLATES .'/style-sample.html'
);

/*$routes['/samples'] = array(
	'class' => 'App\Activity'
	, 'method' => 'createSamples' // TODO: redirecto view/samples
);

$routes['/view/samples'] = array(
	'class' => 'App\Activity'
	, 'method' => 'viewSamples'
	, 'template-data' => TEMPLATES.'/view-activity-sample.html'
);*/

// llamadas API 

$routes['POST /enrolment/create'] = array(
	'class' => 'App\Enrolment'
	, 'method' => 'create'
);