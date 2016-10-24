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
	 * Home.
	 */
	function index()
	{		
		$route = $this->app->request->current();		
		$template = $route['template-data'];

		global $routes;
		$r[] = $template['INI'];
		foreach($routes as $key=>$route) {
			$route['key'] = $key;
			$route['uri'] = HOME . $key;
			$route['label'] = $this->app->locale->say($key);
			$r[] = render($route,$template['ELM']);
		}
		$r[] = $template['END'];
		$this->app->response->add(implode('', $r));

		$this->app->response->html();
	}


}