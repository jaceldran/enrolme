<?php namespace App;
/*
 * ActivityView. 
 * Métodos relacionados con representar el modelo Activity.
 */
class ActivityView extends AppView
{
	protected $context = array(); 

	/*
	 * Constructor.
	 */
	function __construct()
	{
		parent::__construct();		
	}

	/*
	 * Vista de administración de actividades.
	 */ 
	function admin()
	{
		$template = TEMPLATES.'/activity-view-admin.html';
		$model = new ActivityModel();
		$data = $model->read('*');
		$view = renderview($data, $template, $this->context);
		return $view;
	}	

	/*
	 * Formulario create|update.
	 * @param {array} $data Data valores default.
	 */ 
	function create($data) {
		$template = TEMPLATES.'/activity-form-admin.html';
		$this->context['redirect'] = HOME . '/activities/admin';
		$this->context['locale-title'] = $this->context['locale-create']
			. ' ' . $this->context['locale-activity'];
		$data = array($data);
		$view = renderview($data, $template, $this->context);
		return $view;		
	}

	/*
	 * Formulario update.
	 * @param {array} $data Data de activity leído por el controlador.
	 */ 
	function update($data)
	{
		$template = TEMPLATES.'/activity-form-admin.html';
		$this->context['redirect'] = HOME . '/activities/admin';
		$this->context['locale-title'] = $this->context['locale-update']
			. ' ' . $this->context['locale-activity'];
		$data = array($data);
		$view = renderview($data, $template, $this->context);
		return $view;
	}

}