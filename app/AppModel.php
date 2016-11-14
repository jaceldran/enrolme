<?php namespace App;
/*
 * AppModel.
 */
class AppModel
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
		$args = func_get_args();
		$this->build($args);
	}


}