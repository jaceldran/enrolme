<?php namespace App;
/*
 * Activity. Modelo de datos principal.
 * Representa una actividad o evento, con unas fechas-hora de incio y fin 
 * de la actividad y unas fechas-hora de apertura y cierre de inscripción.
 *
 * STATUS: en desarrollo.
 */
class Activity
{
	protected $context = array(); 

	/*
	 * Constructor.
	 */
	function __construct()
	{
		global $app;
		$this->app =& $app;

		$this->storage = new \Zentric\Storage(array(
			'key' => 'activities'
			, 'folder' => STORAGE
			, 'driver' => 'Array'			
		));

		$this->context = array(
			'home' => HOME
		);
	}

	// ----------------------
	// -- MÉTODOS C.R.U.D. --
	// ----------------------

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
	 */
	function find($uid)
	{
		$activity = $this->storage->find('uid',$uid);
		$activity = $this->compute(array($activity));
		return $activity[0];
	}

	/*
	 * Lee los enrolments vinculados a una actividad.
	 */
	function enrollers($uid)
	{
		$model = new Enrolment($uid);
		$content = $model->storage->content();
		$data = $content['data'];
		return $data;
	}

	/*
	 * Campos calculados al leer.
	 */
	function compute($data)
	{		
		foreach($data as $index=>&$elm) {

			// renders de fechas
			$elm = $this->computeDates($elm);

			// duración empieza y termina el mismo día
			if ($elm['render-start-date']===$elm['render-end-date']) {
				$elm['render-duration'] =
					'<span class="icon-calendar"></span>' 
					. ' '.$elm['render-start-date']
					. ' <span class="icon-clock-o"></span>'
					.' '.$elm['render-start-time'] . '&ndash;' . $elm['render-end-time'];
			}

			// enrollers
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

	// -----------------------
	// -- MÉTODOS DE RENDER --
	// -----------------------

	/*
	 *
	 * @param {array} $values
	 *	Lista keys=>values con valores varios como contexto, etc.
	 */
	function addContext($values)
	{
		$this->context = array_merge($this->context, $values);
	}

	/*
	 * @param {array} $data
	 *	El data a representar.
	 * @param {string|array} $template
	 *	El template con el que se representa $data.
	 * @return {string}
	 *	El resultado de la representación.
	 */
	 function renderCollection($data, $template)
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
	 }	


//------------------------------------------------------------------------------
// de aquí para abajo son pruebas de funcionamiento, borrar
//------------------------------------------------------------------------------

	/*
	 * Vista para el home.
	 */
	function viewHome()
	{		
		$route = $this->app->request->current();		
		$template = $route['template-data'];	
		$this->app->response->add( $template['ELM'] );
		$this->app->response->html();
	}

	/*
	 * GET /view/samples. 
	 * Vista de muestras.
	 */
	function viewSamples()
	{
		// configuración de ruta actual
		$route = $this->app->request->current();
		$template = $route['template-data'];

		// este es el data que se va a representar con template-data.
		$content = $this->storage->content();
		$data = $this->compute($content['data']); 

		// proceso normal de render de collection +
		// volcado de variables de contexto como HOME.
		$r[] = $template['INI'];
		foreach($data as $elm) {
			$r[] = render($elm, $template['ELM']);
		}
		$r[] = $template['END'];
		$content = "" . implode("\n\t\t\t", $r) . "";
		$content = render(array(
			'home' => HOME
		), $content);

		$this->app->response->add( $content );
		$this->app->response->html();
	}

	/*
	 * GET /samples. 
	 * Crea un conjunto de actividades de muestra.
	 */
	function createSamples()
	{
		// borra contenido actual de activities
		// y genera un pequeño conjunto aleatorio.		
		$this->storage->delete('*');
		$dt = new \DateTime(date('Y-m-d 09:00'));
		for($i=0; $i<4; $i++) {
			$dt->modify('+1week');
			$elm = array(
				 'name' => 'Nombre de actividad ' . rand(1,100)
				 , 'summary' => 'Resumen de actividad o descripción corta.'
				, 'start' => $dt->format('Y-m-d H:i')
				, 'end' => $dt->modify('+9hours')->format('Y-m-d H:i')
				, 'close' => $dt->modify('-3days')->format('Y-m-d H:i')
				, 'open' => $dt->modify('-3days')->format('Y-m-d H:i') 
			);
			$this->storage->create($elm);
		}

		// redirigir a vista de samples recién creadas.
		$this->app->response->redirect(HOME.'/view/samples');
	}



}