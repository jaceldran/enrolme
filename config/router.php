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
	, 'allow' => array('*')
);

$routes['/activities/admin'] = array ( // vista de acceso admin
	'class' => 'App\ActivityController'
	, 'method' => 'viewAdmin'
	, 'allow' => array('admin')
);

$routes['/activities/create'] = array ( // formulario create
	'class' => 'App\ActivityController'
	, 'method' => 'createAdmin'
	, 'allow' => array('admin')
);

$routes['POST /activities/create'] = array ( // formulario create
	'class' => 'App\ActivityController'
	, 'method' => 'createAdmin'
	, 'allow' => array('admin')
);

$routes['/activities/update/:activity'] = array ( // formulario update
	'class' => 'App\ActivityController'
	, 'method' => 'updateAdmin'
	, 'allow' => array('admin')
);

$routes['POST /activities/update/:activity'] = array ( // procesar $_POST
	'class' => 'App\ActivityController'
	, 'method' => 'updateAdmin'
	, 'allow' => array('admin')
);

//------------------------------------------------------------------------------

// página de inscripción a la actividad :uid
$routes['/in/:uid'] = array ( // enrol me in/xxx...
	'class' => 'App\Page'
	, 'method' => 'enrolme'
	, 'javascript' => '<script src="'.HOME.'/js/enrolment.js"></script>'
	, 'allow' => '*'
);

// listado de participantes de la actividad :uid
$routes['/enrollers/:uid'] = array (
	'class' => 'App\Page'
	, 'method' => 'enrollers'
	, 'allow' => '*'
	//, 'javascript' => '<script src="'.HOME.'/js/enrolment.js"></script>'
);

$routes['/style'] = array ( // muestras de style
	'class' => 'App\Style'
	, 'method' => 'sample'	
	, 'template-data' => TEMPLATES .'/style-sample.html'
	, 'allow' => '*'
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
	, 'allow' => '*'
);