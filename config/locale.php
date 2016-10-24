<?php
/*
 * Configuración de locale.
 */


/*
 * La carpeta donde están los diccionarios.
 * En esta carpeta hay una subcarpeta por cada idioma de la aplicación
 * a la que se le nombre por la key de idioma (es-ES, en-GB...)
 *  
 */
$locale['path'] = LOCALE;

/*
 * El idioma por defecto. La carpeta por tanto es "locale/es-ES"
 */
$locale['lang'] = 'es-ES';

/*
 * Los diccionarios que se cargan por defecto al iniciar el objeto Locale.
 */
$locale['defaults'] = array(
	'routes'
);