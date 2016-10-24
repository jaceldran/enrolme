<?php namespace Zentric;
/*
 * Wrapper para gestión de almacenamiento de datos. 
 */
class Storage
{
	/*
	 * Driver.
	 */
	protected $driver;

	/*
	 * Constructor. Espera settings con los atritubos 
	 * key, path y driver de almacenamiento.
	 */
	function __construct($settings=array())
	{
		$driver = 'Storage' . $settings['driver'];
		$path = __DIR__ . "/$driver.php";
		include_once $path;
		$driver = __NAMESPACE__ . "\\$driver";
		$this->driver = new $driver($settings);
	}
	
	/*
	 * Delegar todas las llamadas al driver.
	 */
	function __call($method, $args)
	{
		if (isset($this->driver)) {
			return call_user_func_array (
				array($this->driver, $method)
				, $args
			);
		}
	}

}