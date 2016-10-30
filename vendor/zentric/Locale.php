<?php namespace Zentric;
/*
 * Locale. Proporciona funcionalidad multiidioma. Permite cargar diccionarios
 * desde el directorio de idioma correspondiente y proporciona la salida en 
 * base a una key de término o expresión.
 */
class Locale
{	
	protected $data = array();
	protected $lang;
	protected $path;

	function __construct($settings=array())
	{
		$this->lang = $settings['lang'];
		$this->path = $settings['path'].'/'.$settings['lang'];
		$this->defaults = $settings['defaults'];
		foreach($this->defaults as $key) {
			$this->load($key);
		}
	}
	
	/*
	 * Recupera un término o un acrónimo con la clave si no lo encuentra.
	 * @param {string} $key 
	 *	La clave del término buscado para traducir.
	 * @param {boolean} $acronym 
	 	Indica si retorna key o acrónimo si no lo encuentra.
	 */
	public function say($key, $acronym=true)
	{	
		if (isset($this->data[$key])) {
			return $this->data[$key];
		} else {
			if ($acronym) {
				return '<acronym title="no-key-locale">'.$key.'</acronym>'; 
			} else {
				return $key;
			}
		}
	}


	/*
	 * Carga diccionarios especificados por keys.
	 * Requiere una ruta definida en this->path
	 * Admite carga de varios archivos load(file1, file2, ...)
	 */
	public function load()
	{		
		$keys = func_get_args();
		foreach($keys as $key) {			
			$file = "{$this->path}/$key.php";
			if (file_exists($file)) {
				$locale = include $file;
				$this->data = array_merge($this->data, $locale);
			} else {
				trigger_error("No encuentra file $file");
			}
		}
	}

	/*
	 * Retorna la lista completa de términos cargados.
	 * Opcionalmente incorpora un prefijo a las keys.
	 */
	function all($prefix=null)
	{
		$data = $this->data;
		if (!empty($prefix)) {
			$data = array();
			array_walk($this->data,	function($value, $index) use ($prefix, &$data) {
				$data[$prefix.$index] = $value;
			});
		}
		return $data;
	}

}