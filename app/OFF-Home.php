<?php namespace App;
/*
 * Home. Controlador de páginas de tipo "Home".
 * En previsión de que puedan agregarse configuraciones para cada perfil.
 */
class Home
{
	/*
	 * Constructor.
	 */
	function __construct()
	{
		global $app;
		$this->app =& $app;
	}

	/*
	 * Home. Muestra las actividades con plazo de inscripción abierto.
	 */
	function index()
	{
		$route = $this->app->request->current();		
		$template = TEMPLATES.'/view-activity-cards.html';
		$model = new Activity();
		$data = $model->read('*');
		$model->addContext(array(
			'url-back'=>$route['uri']
		));
		$this->app->response->add(
			$model->renderCollection($data, $template)
		);
		$this->app->response->html();
	} 
	
}