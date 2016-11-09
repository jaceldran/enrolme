<?php namespace App;
/*
 * ActivityModel. Modelo de datos principal.
 * Representa una actividad o evento, con unas fechas-hora de incio y fin 
 * de la actividad y unas fechas-hora de apertura y cierre de inscripción.
 */
class ActivityModel
{
	protected $context = array(); 

	/*
	 * Constructor.
	 */
	function __construct()
	{
		global $app;
		$this->app =& $app;

		$today = date('Y-m-d');
		$now = date('H:i');
		$this->model = array(
			'uid' => array('rules'=>array())
			, 'name' => array('rules'=>array('required'))
			, 'summary' => array('rules'=>array())
			, 'start' => array(
				'rules'=>array()
				, 'default' => "$today $now"
			)
			, 'end' => array(
				'rules'=>array()
				, 'default' => "$today 00:00"
			)
			, 'open' => array(
				'rules'=>array()
				, 'default' => "$today $now"
			)
			, 'close' => array(
				'rules'=>array()
				, 'default' => "$today 00:00"
			)
		);

		$this->storage = new \Zentric\Storage(array(
			'key' => 'activities'
			, 'folder' => STORAGE
			, 'driver' => 'Array'			
		));
	}

	/*
	 * Valores default.
	 */
	function defaults()
	{
		$data = array();
		foreach($this->model as $key=>$elm) {
			$value = '';
			if(!empty($elm['default'])) {
				$value = $elm['default'];
			}
			$data[$key] = $value;
		}

		$data = end($this->compute( array($data) ));

		return $data;
	}

	/*
	 * Leer actividades.
	 * @param {string} $scope
	 *	Filtro para leer, usualmente un estado de actividad open|close|archive...
	 */
	function read($scope="*")
	{  	
		// por ahora siempre lee todo
		$content = $this->storage->content();
		$data =& $content['data'];		
		return $this->compute($data);
	}

	/*
	 * Lee una actividad por atributo UID
	 * @param {string} $uid Identificador.
	 */
	function find($uid)
	{
		$activity = $this->storage->find('uid',$uid);
		$activity = $this->compute(array($activity));
		return $activity[0];
	}

	/*
	 * Lee los enrolments vinculados a una actividad.
	 * @param {string} $uid Identificador.
	 */
	function enrollers($uid)
	{
		// TO-DO: Usar EnrolmentModel
		return array();
		//$model = new Enrolment($uid);
		//return $model->enrollers();
	}

	/*
	 * Campos calculados al leer.
	 */
	function compute($data)
	{		
		foreach($data as $index=>&$elm) {
			// renders de fechas
			$elm = $this->computeDates($elm);

			// si la duración empieza y termina el mismo día
			if ($elm['render-start-date']===$elm['render-end-date']) {
				$elm['render-duration'] =
					'<span class="icon-calendar"></span>' 
					. ' '.$elm['render-start-date']
					. ' <span class="icon-clock-o"></span>'
					.' '.$elm['render-start-time'] 
						. '&ndash;' . $elm['render-end-time'];
			}

			// enrollers (si hay uid)			
			$elm['enrollers'] = $this->enrollers($elm['uid']);
			$elm['count-enrollers'] = count($elm['enrollers']);			
		}
		return $data;
	}

	/*
	 * Auxiliar de compute. Calcula varios datos derivados para
	 * el $value datetime recibido en formato aaaa-mm-dd hh:mm:ss.
	 */
	function computeDates($elm)
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
	}
	
}