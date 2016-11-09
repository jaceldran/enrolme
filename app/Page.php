<?php namespace App;
/*
 * Page. Controlador de páginas genéricas.
 */
class Page extends AppView
{
	/*
	 * Constructor.
	 */
	/*function __construct()
	{
		global $app;
		$this->app =& $app;

		// variables de contexto útiles para renders.
		//  - locale keys aplicadas a activity como "locale-xxx".
		//  - dirección HOME, para enlaces.
		//$this->app->locale->load('enrolment'); 
		$this->context = $this->app->locale->all('locale-');
		$this->context['home'] = HOME;
		$this->context['host'] = HOST;
	}*/

	/*
	 * GET enrolme/:uid
	 * Enrolme. formulario para inscribirse en actividad.
	 * @param {string} $uid Identificador de actividad.
	 */
	function enrolme($uid)
	{
		$route = $this->app->request->current();
		$model = new Activity();
		$activity = $model->find($uid);
		$template = parse_ini_sections( TEMPLATES.'/form-enrolme.html' );
		$activity = array_merge($activity, $this->context);
		$view = render($activity, $template['ELM']);
		$this->app->response->add($route['javascript'],'javascript');
		$this->app->response->add($view);		
		$this->app->response->html();
	}

	/*
	 * GET enrroles/:uid
	 * Listado de participantes de la actividad $uid (ADMIN)
	 * @param {string} $uid Identificador de actividad.
	 */
	function enrollers($uid)
	{
		$route = $this->app->request->current();
		$model = new Activity();
		$activity = $model->find($uid);
		$activity = array_merge($activity, $this->context);
		$view = renderview (
			$activity['enrollers']
			, TEMPLATES.'/view-enrollers-admin.html'
			, $activity);		
		$this->app->response->add($view);
		$this->app->response->html();
	}
	 
	/*
	 * Home. Presenta la vista de administración de actividades.
	 * TODO: Por tanto, requiere de autenticación.
	 */ 
	function home()
	{

		// TODO:
		$r[] = '<div class="gutter box">';
		$r[] = '<h1>' . __method__ . '</h1>';
		$r[] = '<p>TODO: presentar opciones según perfil 
			<em>Administrador</em> o <em>Anonymous</em></p>';

		$r[] = '<h2>Administrador</h2>
			<p><a href="activities/admin">Actividades</a></p>
		';	
		$r[] = '</div>';			
		$view = implode('',$r);
		
		//$view .= map($this->app->response);

		$this->app->response->add($view);
		$this->app->response->html();
	}

}