<?php

/*
 * Renderizado de un objeto simple.
 * @param {array} $data
 * @param {string} $template.
 * @return {string} $render Renderizado del objeto con el $template.
 */
function render($data, $template)
{
	foreach($data as $key=>$value) {
		$search[] = '%'.strtolower($key).'%';
		$replace[] = $value;
	}	
	return str_replace($search, $replace, $template);
}

/*
 * Parseo básico de un archivo en formato "ini" pero el contenido
 * va directamente en las secciones.
 * @param {string} $template Ruta completa al archivo a parsear.
 * @return {array} $parse Lista asociativa [seccion] => contenido. 
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
 * @param {mixed} lista indeterminada de argumentos.
 * @return {string} $map.
 */
function map()
{	
	$args = func_get_args();
	foreach($args as $arg) {
		$map[] = '<pre>' . print_r($arg, true) . '</pre>';
	} 
	return implode('',$map);
}