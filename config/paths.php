<?php

/*
 * URLS y Carpetas.
 * TODO: Ver si queda más claro distribuyendo las constantes en cada archivo. 
 */

// URL a la home.
define ('HOME', '/enrolme');

// URL ABSOLUTA, para facilitar enlaces
$p = strtolower($_SERVER['SERVER_PROTOCOL']);
$p = substr($p, 0, strpos($p,'/'));
define ('HOST', "$p://".$_SERVER['HTTP_HOST'].HOME );

// Ruta a carpeta de diccionarios locale
define ('LOCALE', './locale');

// Ruta a carpeta de almacenamiento (tipo array)
define ('STORAGE', './storage');

// Ruta a carpeta de templates (relativa a ubicación de este archivo).
define ('TEMPLATES', './templates');