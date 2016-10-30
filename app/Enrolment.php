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
	 */
	function __construct($key=null)
	{
		global $app;
		$this->app =& $app;		
		$this->ini($key);
	}

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

		$this->context = array(
			'home' => HOME
		);		
	}

	// ----------------------
	// -- MÉTODOS C.R.U.D. --
	// ----------------------

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
	 *
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
	 * Leer actividades.
	 * @param {string} $scope
	 *	Filtro para leer, usualmente un estado de actividad open|close|archive...
	 */
	/*function read($scope="*")
	{  	
		// por ahora siempre lee todo
		$content = $this->storage->content();
		$data =& $content['data'];		
		return $this->compute($data);
	}*/

	/*
	 * Lee una actividad por atributo UID
	 */
	/*function find($uid)
	{
		$activity = $this->storage->find('uid',$uid);
		$activity = $this->compute(array($activity));
		return $activity[0];
	}*/

	/*
	 * Campos calculados al leer.
	 */
	/*function compute($data)
	{		
		foreach($data as $index=>&$elm) {
			$elm = $this->computeDates($elm);
		}
		return $data;
	}*/

	/*
	 * Auxiliar de compute. Calcula varios datos derivados para
	 * el $value datetime recibido en formato aaaa-mm-dd hh:mm:ss.
	 */
	/*function computeDates($elm)
	{
		$dates = array('start', 'end', 'open', 'close');
		foreach($dates as $attr) {
			$dt = strtotime($elm[$attr]);			
			$elm["render-$attr-date"] = date('d/m/Y', $dt);
			$elm["render-$attr-day"] = date('d', $dt);
			$elm["render-$attr-month"] = date('M', $dt);
			$elm["render-$attr-year"] = date('Y', $dt);
			$elm["render-$attr-time"] = date('H:i', $dt);
		}
		return $elm;
	}*/

	// -----------------------
	// -- MÉTODOS DE RENDER --
	// -----------------------

	/*
	 *
	 * @param {array} $values
	 *	Lista keys=>values con valores varios como contexto, etc.
	 */
	/*function addContext($values)
	{
		$this->context = array_merge($this->context, $values);
	}*/

	/*
	 * @param {array} $data
	 *	El data a representar.
	 * @param {string|array} $template
	 *	El template con el que se representa $data.
	 * @return {string}
	 *	El resultado de la representación.
	 */
	 /*function renderCollection($data, $template)
	 {
		 if (is_string($template)) {
			 $template = parse_ini_sections($template);
		 }

		 $r[] = $template['INI'];
		 foreach($data as $elm) {
			 $r[] = render($elm, $template['ELM']);
		 }
		 $r[] = $template['END'];

		 $view = implode('', $r);
		 $view = render($this->context, $view);

		 return $view;
	 }*/	


}