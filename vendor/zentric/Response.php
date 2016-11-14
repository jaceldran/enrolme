<?php namespace Zentric;
/*
 * Response.
 */
class Response
{
	protected $template;
	protected $buffers = array();

	/*
	 * Establece template de response.
	 * @param {string} $path
	 *	Ruta física al archivo de template.
	 */
	function setTemplate($path)
	{	
		$this->template = $path;
	}

	/*
	 * Añade contenido a un buffer de salida.
	 * @param {string} $content
	 *	El contenido que se añade al buffer.
	 * @param {string} $buffer
	 *	Identificador del buffer de salida.
	 */
	function add($content, $buffer='main')
	{			
		$this->buffers[$buffer][] = $content;
	}

	/*
	 * Genera la respuesta usando el template y los buffers.
	 * @return {string}
	 *	El contenido o cuerpo de la respuesta.
	 */
	function render()
	{
		ob_start();
		$this->sending = 1;
		include $this->template;
		$content = ob_get_contents();
		ob_end_clean();
		$this->sending = 0;
		return $content;
	}

	/*
	 * Emite los datos recibidos como una respuesta JSON.
	 * @param {array} $data
	 *	Los datos que se envían en formato JSON.
	 */
	function json($data=array())
	{
		$this->headers('json');	
		echo json_encode($data);
	}

	/*
	 * Emite una respuesta HTML con el resultado del render 
	 * del template y los buffers.
	 */
	function html()
	{
		$this->headers('html');	
		echo $this->render();
	}

	/*
	 * Emite una respuesta de texto plano con el resultado del render 
	 * del template y los buffers.
	 */
	function text()
	{
		$this->headers('text');	
		echo $this->render();
	}

	/*
	 * Redirect.
	 */
	function redirect($location)
	{
		header("Location: $location");
		die();
	}

	/*
	 * Establece los headers según tipo de contenido.
	 * @param {string} $type => html|json|text
	 *	Tipo de contenido a enviar 
	 */
	function headers($type='text')
	{	
		// TODO: Determinar mediante configuración si se permite o no
		// la respuesta a peticiones remotas. Por ahora sí, siempre.
		$cors = '*';		
		header("Access-Control-Allow-Origin: $cors");
		header('Access-Control-Allow-Credentials: true');	
		
		switch($type) {
			case 'html':
				header('Content-Type:text/html; Charset=UTF-8');
				break;
			case 'json':
				header('Content-Type:application/javascript; Charset=UTF-8');
				break;
			case 'text':
				header('Content-Type:text/plain; Charset=UTF-8');	
				break;
		}
	}

	/*
	 * Permite recuperar el contenido de cualquier buffer como un método.
	 *
	 * Ejemplo: 	
	 *	response->add("Título de página, "title");
	 *	response->title(); // <<== devuelve "Título de página"
	 * 
	 * @param {string} $key
	 *	Método invocado, usado para identificar el buffer.
	 * @param {array} $args
	 *	Resto de argmentos de llamada. Se ignoran.
	 */
	function __call($key, $args)
	{
		if (isset($this->buffers[$key])) {
			$content = implode("\n", $this->buffers[$key]);
			if ($this->sending) {
				echo $content;
			} else {
				return $content;
			}
		}
	}

}