<?php namespace App;
/*
 * Enrolment. Modelo de datos.
 * Representa una inscripción a una actividad o evento.
 * Cada actividad tendrá un archivo vinculado con la lista de inscripciones. 
 *
 * STATUS: en desarrollo.
 */
class Enrolment
{
	protected $context = array(); 

	/*
	 * Constructor.
	 * @param {string} $key Identificador. 
	 */
	function __construct($key=null)
	{
		global $app;
		$this->app =& $app;		
		$this->ini($key);
	}

	/*
	 * Inicializa contexto.
	 * @param {string} $key Identificador.
	 */
	function ini($key=null)
	{
		$this->model = array(
			'name' => array('rules'=>array('required'))
			, 'surname' => array('rules'=>array())
			, 'email' => array('rules'=>array('required'))
		);

		if ($key) {
			$this->storage = new \Zentric\Storage(array(
				'key' => 'enrolments-'.$key
				, 'folder' => STORAGE
				, 'driver' => 'Array'
			));			
		}		
	}

	/*
	 * POST create
	 */
	function create()
	{
		$request = $_POST;
		$this->ini( $request['storage-key'] );
		unset($request['storage-key']);

		$response['error'] = false;
		$response['request'] = $request;

		$errors = $this->errors($request);
		if (empty($errors)) {
			$request['created'] = date('Y-m-d H:i:s');
			if ($created = $this->storage->create($request)) {
				$response['created'] = $created;
			} else {
				$response['error'] = true;
				$response['message'] = 'Error al crear.';
			}
		} else {
			$response['error'] = true;
			$response['message'] = implode("\n",$errors);
		}

		$this->app->response->json($response);
	}

	/*
	 * Detección de errores en la request.
	 * @param {array} $request.
	 */
	function errors($request) 
	{
		// action create 

		$err = array();

		foreach($this->model as $key=>$config) {
			$is_required = in_array('required',$config['rules']);
			if ($is_required && empty($request[$key])) {
				$err[] = "Dato requerido: $key";
			}
		}

		return $err;
	}

	/*
	 * Lee la lista de datos de participantes.
	 * @param {string} $key Identificador de storage.
	 * @return {array} $data.
	 */
	function enrollers($key=null)
	{
		if (!empty($key)) {
			$this->ini($key);
		}
		$content = $this->storage->content();
		$data = $this->compute($content['data']);
		return $data;		
	}

	/*
	 * Datos calculados al leer.
	 * @param {array} $data.
	 * @return {array} $data. 
	 */
	function compute($data)
	{
		foreach($data as $index=>&$elm) {
			$elm['render-created'] = date('d/m/Y H:i', strtotime($elm['created']));
		}
		return $data;
	}
}