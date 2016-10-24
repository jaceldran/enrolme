<?php namespace Zentric;
/*
 * Request.
 */
class Request
{
	protected $route;
	protected $routes;
	protected $defaults;
	protected $uri;

	/*
	 * Constructor.
	 * @param {array} $router 
	 *	Configuración de rutas de la app.
	 */
	function __construct($router)
	{
		$this->defaults = $router['defaults'];
		$this->routes = $router['routes'];
		$this->uri =$_SERVER['REQUEST_METHOD']
			.' '.str_replace(HOME,'',$_SERVER['REQUEST_URI']);
		$this->route = $this->match();
	}

	/*
	 * Identifica configuración de la ruta actual.
	 * @return {array|null} $route 
	 *	Configuración de ruta actual	o null, si no existe.
	 */
	function match()
	{		
		foreach($this->routes as $pattern=>$route) {
			// defaults
			$route = array_merge($this->defaults, $route);

			// si patten no especifica método, asignar GET
			if (substr($pattern,0,1)==='/') {
				$pattern = "GET $pattern";
			}
			$route['pattern'] = $pattern;
			$route['params'] = array();

			// si coincide uri con pattern es que no hay placeholders.
			if ($this->uri===$pattern) {
				return $route;
			}	

			// comprobar si pattern incluye placeholders y parsear.
			$params = array();
			$placeholders = substr_count($pattern, ':');
			if ($placeholders) {
				// pattern sin placeholders
				$corepattern = array_shift(explode(':', $pattern)); 
				$keys = explode('/',substr($pattern,strlen($corepattern)));
				$pattern = $corepattern;				
				
				// si la uri comienza igual que el pattern...
				if (substr($this->uri,0,strlen($pattern))===$pattern) {
					
					// ...y tiene el mismo número de parámetros.
					$values = explode(':',substr($this->uri, strlen($pattern)));
					if (count($keys)===$placeholders 
						&& count($values)===$placeholders) {
						
						// crear asociativa de params.
						foreach($keys as $index=>$key) {
							$params[substr($key,1)] = $values[$index];	
						}
						$route['params'] = $params;
						return $route;
					}
				}
			}
		}
	}

	/*
	 * Permite reconfigurar $this->route.
	 * @param {array} $current 
	 *	Configuración route que reemplaza a la actual.
	 */
	function setCurrent($current)
	{
		$this->route = $current;
	}

	/*
	 * Leer la configuración de ruta actual.
	 * @return {array} $this->route 
	 *	Configuración de ruta actual
	 */
	function current()
	{
		return $this->route;
	}

}