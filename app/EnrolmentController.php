<?php namespace App;
/*
 * EnrolmentController. 
 * Métodos relacionados con la gestión de inscripciones a una Activity.
 */
class EnrolmentController extends AppController
{
	/*
	 * Constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = new EnrolmentView();
		$this->model = new EnrolmentModel();
	}

	/*
	 * Método genérico de envío response.
	 */
	function html($view)
	{
		$this->app->response->add($view);
		$this->app->response->html();
	}	


	/*
	 * Vista de enrolments de una activity.
	 */
	function viewAdmin($activity)
	{
		if ($_POST) {
			//$updated = $this->model->update($_POST);
			//if ($updated) {
			//	$this->app->response->redirect($_POST['redirect']);
			//}			
		} else {
			$activity = $this->model->read($activity);
			$data = $activity['enrolments'];
			unset($activity['enrolments']);
			$this->context += $activity;
			//$this->context['locale-title'] = $this->context['locale-enrolments'];
			//$data['activity-uid'] = $activity;
			//$data['redirect'] = HOME . '/activities/update/'.$data['uid'];
			//$this->context['back'] = HOME . '/activities/update/'.$activity['uid'];
		}
		$view = $this->view->admin($data, $this->context);
		$this->html($view);
	}

// ----------------------------------------------------------------------------


// ------------------------------------------------------------
// TO-DO: Todos los métodos "xxxAdmin" requieren autenticación.
// ------------------------------------------------------------

	/*
	 * Vista de administración de actividades.
	 * Se lee a través del archivo índice por año.
	 */
	function OFFviewAdmin($yyyy=null)
	{
		if (empty($yyyy)) {
			$yyyy = date('Y');
		}
		$index = new IndexModel(array(
			'key-storage' => "activities-$yyyy" 
		));
		$this->context['yyyy'] = $yyyy;
		$data = $this->model->compute($index->read());
		$view = $this->view->admin($data, $this->context);
		$this->html($view);
	}

	/*
	 * Procesa $_POST, si está presente.
	 * Presenta el formulario para create activity.
	 * @parama {string} $activity Identificador de $activity
	 */
	function OFFcreateAdmin()
	{
		if ($_POST) {			
			$created = $this->model->create($_POST);
			if ($created) {
				$this->app->response->redirect($_POST['redirect']);
			}
		} else {
			$data = $this->model->defaults();
			$data['action'] = 'create';
			$data['back'] = HOME . '/activities/admin';
			$data['redirect'] = HOME . '/activities/update/'.$data['uid'];
		}
		$view = $this->view->create($data);
		$this->html($view);
	}

	/*	 
	 * Procesa $_POST, si está presente.
	 * Presenta formulario update activity.
	 * @parama {string} $activity Identificador de $activity
	 */
	function OFFupdateAdmin($activity)
	{		
		if ($_POST) {
			$updated = $this->model->update($_POST);
			if ($updated) {
				$this->app->response->redirect($_POST['redirect']);
			}			
		} else {
			$data = $this->model->read($activity);
			$data['action'] = 'update';
			$data['redirect'] = HOME . '/activities/update/'.$data['uid'];
			$data['back'] = HOME . '/activities/admin';
		}
		$view = $this->view->update($data);
		$this->html($view);
	}

}