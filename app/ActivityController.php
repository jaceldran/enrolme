<?php namespace App;
/*
 * ActivityController. 
 * Métodos relacionados con la gestión de solicitudes del modelo Activity.
 */
class ActivityController extends AppController
{
	/*
	 * Constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = new ActivityView();
		$this->model = new ActivityModel();
	}

// ------------------------------------------------------------
// TO-DO: Todos los mmétodos "xxxAdmin" requieren autenticación.
// ------------------------------------------------------------

	/*
	 * Vista de administración de actividades.
	 * Se lee a través del archivo índice por año.
	 */
	function viewAdmin($yyyy=null)
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
	function createAdmin()
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
	function updateAdmin($activity)
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

	/*function viewEnrolments($activity)
	{		
		if ($_POST) {
			//$updated = $this->model->update($_POST);
			//if ($updated) {
			//	$this->app->response->redirect($_POST['redirect']);
			//}			
		} else {
			$data = $this->model->read($activity);			
			//$data['action'] = 'update';
			//$data['redirect'] = HOME . '/activities/update/'.$data['uid'];
			$data['back'] = HOME . '/activities/admin';
		}
		$view = $this->view->enrolments($data);
		$this->html($view);
	}*/

	/*
	 * Método genérico de envío response.
	 */
	function html($view)
	{
		$this->app->response->add($view);
		$this->app->response->html();
	}

}