<?php namespace Zentric;
/*
 * Driver de archivos de almacenamiento en disco.
 * Indicado para volúmenes de datos no excesivos. 
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
		$this->build();
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
	 * Crea un elemento nuevo en el almacenamiento actual.
	 * @param {array} $elm
	 *	Array asociativa con atributos y valores del elemento.
	 */
	function create($elm)
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
	function read($index)
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
	function update($index, $values)
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
	function delete($index)
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
	function find($attr, $value)
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
	function build()
	{		
		if (file_exists($this->file)) {
			return;
		}
		$content = array(
			'file' => basename($this->file)
			, 'created' => date('Y-m-d H:i:s')
			, 'updated' => null
			, 'model' => array()
			, 'data' => array()
		);
		return $this->save($content);
	}

	/*
	 * Genera un archivo de índice nombrado como  "[key]-by-[attr]".
	 * @param {string} $attr
	 *	El atributo para construir el indice. No debe haber duplicados.
	 */	
	function buildIndex($attr)
	{
		$content = $this->content();
		$key = "{$this->key}-by-$attr";
		$file = $this->path($key);
		foreach($content['data'] as $index=>$elm) {
			$data[$elm[$attr]] = $index;
		}
		return $this->save($data, $file);
	}

	/*
	 * Recupera el contenido de un archivo.
	 * @param {string} $file
	 *	Ruta física del archivo a leer.
	 */
	function content($file=null)	
	{
		if (empty($file)) {
			$file = $this->file;
		}
		$content = include $file;
		return $content;
	}

	/*
	 * Guarda el contenido de un archivo.
	 * @param {string} $content
	 *	El contenido del archivo
	 * @param {string} $file
	 *	Ruta física del archivo a guardar.
	 * @return {boolean}
	 *	Resultado de la operación.
	 */
	function save($content, $file=null)
	{
		if (empty($file)) {
			$file = $this->file;
		}
		$content = '<?php return ' . var_export($content, true) . ';';
		return file_put_contents($file, $content);
	}

}