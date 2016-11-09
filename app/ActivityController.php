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
	 */
	function viewAdmin()
	{		
		$view = $this->view->admin();
		$this->html($view);
	}

	/*
	 * Formulario create activity.
	 * @parama {string} $activity Identificador de $activity
	 */
	function createAdmin()
	{		
		$data = $this->model->defaults();
		$view = $this->view->create($data);
		$this->html($view);
	}

	/*
	 * Formulario update activity.
	 * @parama {string} $activity Identificador de $activity
	 */
	function updateAdmin($activity)
	{		
		$data = $this->model->find($activity);
		$view = $this->view->update($data);
		$this->html($view);
	}

	/*
	 * Método genérico de envío response.
	 */
	function html($view)
	{
		$this->app->response->add($view);
		$this->app->response->html();
	}

}