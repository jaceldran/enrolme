<?php namespace App;
/*
 * EnrolmentView. 
 * Métodos relacionados con representar el modelo Enrolment.
 */
class EnrolmentView extends AppView
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
	/*function admin($data=array(), $context=array())
	{
		$this->context += $context;
		$template = TEMPLATES.'/activity-view-admin.html';
		$view = renderview($data, $template, $this->context);
		return $view;
	}*/	

	/*
	 * Formulario create|update.
	 * @param {array} $data Data valores default.
	 */ 
	/*function create($data) {
		$template = TEMPLATES.'/activity-form-admin.html';
		$this->context['redirect'] = $data['redirect'];
		$this->context['back'] = $data['back'];
		$this->context['locale-title'] = $this->context['locale-create']
			. ' ' . $this->context['locale-activity'];
		$data = array($data);
		$view = renderview($data, $template, $this->context);
		return $view;		
	}*/

	/*
	 * Formulario update.
	 * @param {array} $data Data de activity leído por el controlador.
	 */ 
	/*function update($data)
	{
		$template = TEMPLATES.'/activity-form-admin.html';
		$this->context['redirect'] = $data['redirect'];
		$this->context['back'] = $data['back'];
		$this->context['locale-title'] = $this->context['locale-update']
			. ' ' . $this->context['locale-activity'];
		$data = array($data);
		$view = renderview($data, $template, $this->context);
		return $view;
	}*/

	/*
	 * Vista de administración de enrolments.
	 */ 
	function admin($data=array(), $context=array())
	{
		$template = parse_ini_sections(TEMPLATES.'/enrolments-view-admin.html');
		$this->context += $context;		
		$view = renderview($data, $template, $this->context);
		return $view;
	}		

}