<?php


class frontController
{
	private $controlador = "Home";
	private $metodo = "index";
	private $params;
	
	public function index()
	{
	
		$url = $_SERVER["REQUEST_URI"];
		$path = trim(parse_url($url, PHP_URL_PATH), "/");

		try 
		{
			@list($appname, $controlador, $metodo, $params) = explode("/", $path, 4);
			@$params = (explode("/", $params));
			if($controlador==null)
			{
				$controlador = $this->controlador;
			}
			$miControlador = $this->cargarControlador($controlador);
			$miControlador->setParametros($params);

			if($metodo==null)
			{
				$metodo = $this->metodo;
			}
			$stringMetodo = $metodo;
			$miControlador->$stringMetodo();
		} 
			catch (Exception $e)
			{
				$e->getMessage();
			}

	}

	function cargarControlador($controlador)
	{
		$controlador = ucfirst(strtolower($controlador));
		$urlFile = 'controladores/' . $controlador . '.php';

		if(file_exists($urlFile))
		{
			include $urlFile;
			$class = $controlador;
			$controlador = new $class();
			return $controlador;
		} else
			{
				return null;	
			}
	}
}

$frontController = new FrontController();
$frontController->index();

