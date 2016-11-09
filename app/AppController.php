<?php namespace App;
/*
 * AppController. Clase padre para controladores de la app. 
 */
class AppController
{
	protected $context = array();

	/*
	 * Constructor.
	 */
	function __construct()
	{
		global $app;
		$this->app =& $app;
		$this->route = $this->app->request->current();
		$this->context = $this->app->locale->all('locale-');
		$this->context['home'] = HOME;				
	}

}