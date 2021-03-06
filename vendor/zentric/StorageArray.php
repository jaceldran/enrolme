<?php namespace Zentric;
/*
 * Driver de archivos de almacenamiento en disco.
 * Indicado para volúmenes de datos no excesivos.
 * Pensado para un contenedor de elementos que se almacenan el el data.
 */ 
class StorageArray
{
	protected $key;
	protected $folder;
	protected $file;

	/*
	 * Constructor.
	 */
	function __construct($settings=array())
	{
		$this->key = $settings['key'];
		$this->folder = $settings['folder']; 
		$this->file = $this->path();
	}

	/*
	 * Calcula la ruta física correspondiente a un identificador.
	 * @param {string} $key
	 *	Identificador de almancenamiento.
	 */
	function path($key=null)
	{
		if (empty($key)) $key = $this->key;
		return $this->folder."/$key.php";
	}
	
	/*
	 * Create.
	 */
	function create($values=array(), $file=null)
	{
		if (empty($file)) {
			$file = $this->file;
		}
		if (file_exists($file)) {
			return;
		}		
		$content = array (
			'file' => basename($this->file)
			, 'created' => date('Y-m-d H:i:s')
			, 'updated' => null
		);
		$values = array_merge($content, $values);
		$created = $this->update($values, $file);
		return $created;
	}

	/*
	 * Read.
	 * @param {string} $file Ruta física del archivo a leer. 
	 */
	function read($file=null)
	{
		if (empty($file)) {
			$file = $this->file;
		}
		$content = array();
		if (file_exists($file)) {
			$content = include $file;
		}		
		return $content;		
	}

	/*
	 * Update.
	 * @param {array} $values Asociativa de atributos y valores.
	 * @param {string} $file Ruta física del archivo.
	 * @return {boolean} Resultado de la operación.
	 */
	function update($values, $file=null)
	{
		if (empty($file)) {
			$file = $this->file;
		}
		$values = array_merge($this->read($file), $values);
		$content = '<?php return ' . var_export($values, true) . ';';
		$updated = file_put_contents($file, $content);
		return $updated;
	}

	/*
	 * Registra un valor en un atributo del almacenamiento actual.
	 * @param {string} $key Key de registro.
	 * @param {mixed} $value Atributos y valores del elemento.
	 * @param {string} $attr Atributo donde se añade el elemento.
	 * @return {boolean} Resultado de la operación.
	 */
	function register($key, $value, $attr='data', $file=null)
	{
		$content = $this->read($file);
		if (empty($content[$attr])) {
			$content[$attr] = array();
		}
		$content[$attr][$key] = $value;
		return $this->update($content);
	}

	/*
	 * Añade un valor a un atributo del almacenamiento actual.
	 * @param {array} $elm Atributos y valores del elemento.
	 * @param {string} $attr Atributo donde se añade el elemento.
	 * @return {boolean} Resultado de la operación.
	 */
	function add($value, $attr='data', $file=null)
	{
		$content = $this->read($file);
		if (empty($content[$attr])) {
			$content[$attr] = array();
		}
		$content[$attr][] = $value;
		return $this->update($content);
	}

// REFACTORIZANDO --------------------------------------------------------------

	/*
	 * Crea un elemento nuevo en el almacenamiento actual.
	 * @param {array} $elm
	 *	Array asociativa con atributos y valores del elemento.
	 */
	function OFFcreate($elm)
	{
		$content = $this->content();
		$elm['uid'] = strtoupper(uniqid());
		$content['data'][] = $elm;	
		return $this->save($content);
	}

	/*
	 * Lee un elemento del almacenamiento actual.
	 * @param {numeric} $index
	 *	Posición en base 0 del elemento.
	 */
	function OFFread($index)
	{
		$content = $this->content();
		$elm = $content['data'][$index];
		$elm['index'] = $index;		
		return $elm;		
	}

	/*
	 * Actualiza un elemento del almacenamiento actual.
	 * @param {numeric} $index
	 *	Posición en base 0 del elemento.
	 * @param {array} $values
	 *	Array asociativa de atributos y valores a actualizar.
	 */
	function OFFupdate($index, $values)
	{
		$content = $this->content();
		$elm = $content['data'][$index];
		$content['data'][$index] = array_merge($elm, $values);
		return $this->save($content);		
	}

	/*
	 * Elimina  un elemento del almacenamiento actual.
	 * @param {numeric} $index
	 *	Posición en base 0 del elemento.
	 */
	function OFFdelete($index)
	{
		$content = $this->content();
		if ($index==='*') {
			$content['data'] = array();
		} else {
			unset($content['data'][$index]);
		}				
		return $this->save($content);
	}

	/*
	 * Localiza un elemento por un valor de atributo.
	 * Requiere que exista el correspondiente archivo de índice.
	 * @param {string} $attr
	 *	Nombre del atributo.
	 * @param {string} $value
	 *	Valor del atributo del elemento buscado.
	 */
	function OFFfind($attr, $value)
	{
		$content = $this->content();
		$key = "{$this->key}-by-$attr";
		$file = $this->path($key);
		$keys = $this->content($file);
		$index = $keys[$value];
		$elm = $content['data'][$index];
		$elm['index'] = $index;
		return $elm;		
	}

	/*
	 * Genera el archivo de almacenamiento actual.
	 * Es un archivo PHP que contiene un array con los datos gestionados
	 * y otra información relativa al propio fichero.
	 */
	function OFFbuild($settings=array())
	{		
		if (file_exists($this->file)) {
			return;
		}
		$content = array(
			'file' => basename($this->file)
			, 'created' => date('Y-m-d H:i:s')
			, 'updated' => null
		);
		if (!empty($settings['attrs'])) {
			$content = array_merge($content, $settings['attrs']);
		}
		$content ['data'] = array();
		return $this->save($content);
	}

	/*
	 * Genera un archivo de índice nombrado como "[key]-by-[attr]".
	 * @param {string} $attr
	 *	El atributo para construir el indice. No debe haber duplicados.
	 */	
	function OFFbuildIndex($attr)
	{
		$content = $this->content();
		$key = "{$this->key}-by-$attr";
		$file = $this->path($key);
		foreach($content['data'] as $index=>$elm) {
			$data[$elm[$attr]] = $index;
		}
		return $this->save($data, $file);
	}





}