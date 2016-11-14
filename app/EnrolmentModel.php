<?php namespace App;
/*
 * EnrolmentModel.
 */
class EnrolmentModel extends AppModel
{
	protected $context = array(); 

	/*
	 * Constructor de modelo, invocado por parent::__construct.
	 */
	function build($args=null)
	{
		$this->model = array(
			'name' => array('rules'=>array('required', 'text'))
			, 'surname' => array('rules'=>array('required','textarea'))
			, 'email' => array('rules'=>array('required','email'))
			, 'created' => array('rules'=>array('required','datetime'))
		);
	}

	/*
	 * 
	 */
	function read($activity)
	{
		$model = new ActivityModel();
		$activity = $model->read($activity);
		return $activity;
		
		$data = $activity['enrolments'];
		return $data;
	}

	/*
	 * Campos calculados al leer.
	 */
	function compute($data)
	{
		foreach($data as $index=>&$elm) {

		}
		return $data;
	}

//------------------------------------------------------------------------------	

	/*
	 * Añade un enrolment a la activity.
	 * @param {string} $activity Identificador de activity.
	 * @param {array} $data Datos del enrolment.
	 */
	function OFFcreate($activity, $data)
	{
		/*$values = $data; // TO-DO: ajustar.	
		$values['enrolments'] = array(); // enrolments vinculados.
		$this->storage = new \Zentric\Storage(array(
			'key' => 'activity-' . $data['uid']
			, 'folder' => STORAGE
			, 'driver' => 'Array'
		));
		$created = $this->storage->create($values);
		$this->updateIndex($data);
		return $created;*/
	}

	/*
	 * Read.
	 * @param {string} $activity
	 *	Filtro para leer, usualmente un uid o scope de actividad 
	 * @param {string} $scope --- POR AHORA SIN USO
	 *	Identificador de scope open|close|archive...
	 */
	function OFFread($activity, $scope="enrolment")
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
	function OFFupdate($data)
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


}