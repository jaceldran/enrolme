<?php namespace App;
/*
 * AppView.
 */
class AppView
{
	/*
	 * Constructor.
	 */
	function __construct()
	{
		global $app;
		$this->app =& $app;
		$this->context = $this->app->locale->all('locale-');
		$this->context['home'] = HOME;
		$this->navMain();
	}

	/*
	 * Establece el buffer de salida para el nav principal.
	 * TODO: componer opciones según permisos establecidos en el router.
	 */
	function navMain()
	{
		$r = array();

		$r[] = 	'<a class="action" href="'.HOME.'">
					<span class="icon-home"></span>
					<span>Home</span>
				</a>';

		$r[] = 	'<a class="action" href="'.HOME.'/activities/admin">
					<span class="icon-list-ul"></span>
					<span>Actividades</span>
				</a>';

		$nav = implode('',$r);

		$this->app->response->add($nav,'navMain');		
	}

}