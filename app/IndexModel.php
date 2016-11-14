<?php namespace App;
/*
 * IndexModel. 
 * Representa un índice de archivos agrupados por un atributo común
 * como, por ejemplo, el año en que se inicia una actividad.
 */
class IndexModel extends AppModel
{
	protected $context = array(); 

	/*
	 * Constructor de modelo, invocado por parent::__construct.
	 * @param {$args} Argumentos de llamada.
	 */
	function build($args=null)
	{
		$settings = $args[0]; // espera que $args[0] sea el array de settings.		
		$this->storage = new \Zentric\Storage(array(
			'key' => $settings['key-storage']
			, 'folder' => STORAGE
			, 'driver' => 'Array'			
		));
		$this->storage->create();
	}


	/*
	 * @param {string} $key Key de registro.
	 * @param {string} $value Valor registrado.
	 */
	function register($key, $value)
	{
		$this->storage->register($key, $value);
	}

	/*
	 * Read. Leer el contenido de los archivos referenciados en el index.
	 */
	function read()
	{
		$content = $this->storage->read();
		$data = array();
		foreach($content['data'] as $key=>$value) {
			$storage = new \Zentric\Storage(array(
				'key' => $key
				, 'folder' => STORAGE
				, 'driver' => 'Array'			
			));			
			$data[] = $storage->read();			 
		}
		return $data;
	}

	/*
	 * Update.
	 * @param {array} $data
	 */
	/*function update($data)
	{
		$values = $data; // TO-DO: ajustar keys.
		return $this->storage->update($values);
	}*/

}