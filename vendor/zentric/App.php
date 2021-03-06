<?php namespace Zentric;
/*
 * App. Contexto de aplicación.
 */
class App
{	
	/*
	 * Constructor.
	 * @param {array} $settings
	 *	Configuración de router y locale.
	 */
	function __construct($settings)
	{
		session_start();
		$this->locale = new Locale($settings['locale']);
		$this->request = new Request($settings['router']);
		$this->response = new Response();		
	}

	/*
	 * Identifica la configuración de ruta actual.
	 * Establece templates para la response.
	 * Invoca el método vinculado a la ruta.
	 */
	function start()
	{
		if ($current = $this->request->current()) {

			// establecer template
			if (!empty($current['template-app'])) {				
				$this->response->setTemplate($current['template-app']);
			}

			// si incorpora 'template-data', entregar ya parseado.
			if (!empty($current['template-data'])) {
				$current['template-data'] = 
					parse_ini_sections($current['template-data']);
				$this->request->setCurrent($current);
			}

			// instanciar objeto e invocar método con parámetros.
			$class = $current['class'];
			$method = $current['method'];
			$params = $current['params'];
			$object = new $class();
			call_user_func_array(array($object, $method),$params);

		} else {

			trigger_error('404 no encontrado');
			
		}
	}
}