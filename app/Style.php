<?php namespace App;
/*
 * Style. Controlador de páginas relacionadas con el estilo del sitio.
 * En previsión de que puedan agregarse otras funcionalidades.
 */
class Style
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
	 * Una muestra de cómo se ve el estilo del sitio aplicado a los diversos
	 * elementos html y componentes. Simplemente carga el contenido del 
	 * template-data, tal cual.  
	 */
	function sample()
	{		
		$route = $this->app->request->current();		
		$template = $route['template-data'];
		$this->app->response->add(
			$template['INI']
			. $template['ELM']
			. $template['END']
		);
		/*global $routes;//*
		$r[] = $template['INI'];
		foreach($routes as $key=>$route) {
			$route['key'] = $key;
			$route['uri'] = HOME . $key;
			$route['label'] = $this->app->locale->say($key);
			$r[] = render($route,$template['ELM']);
		}
		$r[] = $template['END'];
		$this->app->response->add(implode('', $r));*/
		$this->app->response->html();
	}


}