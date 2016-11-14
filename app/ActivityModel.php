<?php namespace App;
/*
 * ActivityModel. Modelo de datos principal.
 * Representa una actividad o evento, con unas fechas-hora de incio y fin 
 * de la actividad y unas fechas-hora de apertura y cierre de inscripción.
 */
class ActivityModel extends AppModel
{
	protected $context = array(); 

	/*
	 * Constructor de modelo, invocado por parent::__construct.
	 */
	function build($args=null)
	{
		// model
		$today = date('Y-m-d');
		$now = date('H:i');
		$this->model = array(
			'uid' => array(
				'rules'=>array()
				, 'default' => strtoupper(uniqid())
			)
			, 'name' => array('rules'=>array('required'))
			, 'summary' => array('rules'=>array())
			, 'places' => array(
				'rules'=>array('number')
				, 'default' => "10"
			)
			, 'start-date' => array(
				'rules'=>array('date','required')
				, 'default' => $today
			)
			, 'start-time' => array(
				'rules'=>array('time')
				, 'default' => $now
			)
			, 'end-date' => array(
				'rules'=>array('date')
				, 'default' => "$today"
			)
			, 'end-time' => array(
				'rules'=>array('time')
				, 'default' => "00:00"
			)
			, 'open-date' => array(
				'rules'=>array('date')
				, 'default' => $today
			)
			, 'open-time' => array(
				'rules'=>array('time')
				, 'default' => $now
			)
			, 'close-date' => array(
				'rules'=>array('date')
				, 'default' => $today
			)
			, 'close-time' => array(
				'rules'=>array('time')
				, 'default' => "00:00"
			)
		);
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
		$data = end($this->compute(array($data)));
		return $data;
	}

	/*
	 * Create.
	 * @param {string} $data Usualmente el contenido del  _POST.
	 */
	function create($data)
	{
		$values = $data; // TO-DO: ajustar.	
		$values['enrolments'] = array(); // enrolments vinculados.
		$this->storage = new \Zentric\Storage(array(
			'key' => 'activity-' . $data['uid']
			, 'folder' => STORAGE
			, 'driver' => 'Array'
		));
		$created = $this->storage->create($values);
		$this->updateIndex($data);
		return $created;
	}

	/*
	 * Read.
	 * @param {string} $activity
	 *	Filtro para leer, usualmente un uid o scope de actividad 
	 * @param {string} $scope --- POR AHORA SIN USO
	 *	Identificador de scope open|close|archive...
	 */
	function read($activity, $scope="activity")
	{ 	
		switch($scope) {
			case 'activity':
				$this->storage = new \Zentric\Storage(array(
					'key' => "activity-$activity"
					, 'folder' => STORAGE
					, 'driver' => 'Array'			
				));
				$data = $this->storage->read();
				break;
		}
		$data = end($this->compute(array($data)));
		return $data;
	}

	/*
	 * Update.
	 * @param {array} $data
	 */
	function update($data)
	{
		$values = $data; // TO-DO: ajustar keys.
		$this->storage = new \Zentric\Storage(array(
			'key' => 'activity-' . $data['uid']
			, 'folder' => STORAGE
			, 'driver' => 'Array'		
		));
		$updated = $this->storage->update($values);
		$this->updateIndex($data);
		return $updated;
	}

	/*
	 * Registra archivo de activity $data en la tabla índice del año.
	 */
	function updateIndex($data)
	{		
		// la key de almacenamiento para el index por año.		
		$key = 'activities-' . date('Y',strtotime($data['start-date']));
		$settings = array(
			'key-storage' => $key
		);		
		$index = new IndexModel($settings);
		$key = 'activity-' . $data['uid'];
		$value = 'activity-' . $data['uid'];
		$index->register($key, $value);		
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
			$elm['render-duration'] = '';
			if ($elm['iso-start-date']===$elm['iso-end-date']) {
				$elm['render-duration'] =
					'<span class="icon-calendar"></span>' 
					. ' '.$elm['render-start-date']
					. ' <span class="icon-clock-o"></span>'
					.' '.$elm['render-start-time'] 
						. '&ndash;' . $elm['render-end-time'];
			} else {
				$elm['render-duration'] = 
					$elm['render-start-date'].' '.$elm['render-start-time']
					. ' &ndash; ' 
					. $elm['render-end-date'].' '.$elm['render-end-time']
					;
			}

			// enrolments
			if (empty($elm['enrolments'])) {
				$elm['enrolments'] = array();
			}
			$elm['count-enrolments'] = count($elm['enrolments']);
		}
		return $data;
	}

	/*
	 * Auxiliar de compute. Calcula varios derivados para valores date|time.
	 */
	function computeDates($elm)
	{
		$keys = array('start', 'end', 'open', 'close');
		foreach($keys as $attr) {
			$dt = strtotime($elm["$attr-date"].' '.$elm["$attr-time"]);
			$elm["iso-$attr-date"] = date('Y-m-d', $dt);
			$elm["iso-$attr-time"] = date('H:i', $dt);
			$elm["render-$attr-date"] = date('d/m/Y', $dt);
			$elm["render-$attr-time"] = date('H\hi', $dt);
		}
		return $elm;
	}
	
////////////////////////////////////////////////////////////////////////////////

	/*
	 * Lee una actividad por atributo UID
	 * @param {string} $uid Identificador.
	 */
	function OFFfind($uid)
	{		
		$defaults = $this->defaults(); // para aplicar defaults ( en prueba )
		$activity = $this->storage->find('uid',$uid);
		$activity = end($this->compute(array($activity)));
		$activity = array_merge( $defaults, $activity);
		return $activity;
	}

	/*
	 * Lee los enrolments vinculados a una actividad.
	 * @param {string} $uid Identificador.
	 */
	function OFFenrollers($uid)
	{
		// TO-DO: Usar EnrolmentModel
		return array();
		//$model = new Enrolment($uid);
		//return $model->enrollers();
	}


}