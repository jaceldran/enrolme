<?php
/*
 * Helpers. Funciones útiles.
 */

/*
 * Render de una colección de elementos ($data) usando $template.
 * @param {array} $data.
 * @param {array|string} $template.
 * @param {array} $marks Marcas que aplican al todo el documento.
 */
function renderview($data, $template, $marks=array()) {

	if (is_string($template)) {
		$template = parse_ini_sections($template);
	}

	$r[] = $template['INI'];
	foreach($data as $index=>$elm) {
		$elm['index'] = $index;			// en base 0
		$elm['position'] = $index+1; 	// en base 1
		$r[] = render($elm, $template['ELM']);
	}
	$r[] = $template['END'];

	$view = implode('', $r);
	$view = render($marks, $view);

	return $view;
}

/*
 * Representa un objeto con una plantilla.
 * @param {array} $data
 *	El objeto a representar. Array asociativo de atributos y valores.
 * @param {string} $template.
 *	La plantilla utilizada para representar el objeto.
 * @return {string} $render 
 *	Representación del objeto con la plantilla.
 */
function render($data, $template)
{
	$search = array();
	$replace = array();
	foreach($data as $key=>$value) {
		if (!is_scalar($value)) continue;
		$search[] = '%'.strtolower($key).'%';
		$replace[] = $value;
	}	
	return str_replace($search, $replace, $template);
}

/*
 * Parseo básico de un archivo en formato "ini" pero el contenido
 * va directamente en las secciones.
 * @param {string} $template 
 *	Ruta completa al archivo a parsear.
 * @return {array} $parse 
 *	Lista asociativa [seccion] => contenido. 
 */
function parse_ini_sections($path)
{
	if (!file_exists($path)) {return;}
	$parse = array();
	$content = file($path); 
	foreach($content as $line) {
		$line = trim($line);
		if (empty($line)) continue;
		if (substr($line,0,1)==='[') {
			$section = str_replace(array('[',']'),'',$line);
			$parse[$section] = '';
		} else {
			$parse[$section]  .= $line;
		}
	}
	return $parse;
}

/*
 * Representa todas los argumentos recibidos en formato print_r. 
 * @param {mixed} 
 *	Lista indeterminada de argumentos.
 * @return {string} 
 *	Representación de los argumentos recibidos.
 */
function map()
{	
	$args = func_get_args();
	foreach($args as $arg) {
		$map[] = '<pre>' . print_r($arg, true) . '</pre>';
	} 
	return implode('',$map);
}