<?php 

/*
include 'libs/Controlador.php';
*/

class Controlador
{

private $parametros;

public function setParametros($parametros)
{
	$this->parametros = $parametros;
}

protected function getParametros()
{
	return $this->parametros;
}

protected function cargarModelo($modelo)
 {
 	$modelo = ucfirst(strtolower($modelo));
 	$urlFile = 'modelos/' . $modelo .'.php';

 	if(file_exists($urlFile))
 	{
 		include $urlFile;
 		$class = $modelo;
 		$modelo = new $class();
 		return $modelo;

 	} else 
 		{
 			return null;
 		} 

 }

protected function cargarVista($vista)
 {
 	
	$vista = (strtolower($vista));
 	$urlFile = 'vistas/' . $vista .'.php';

 	if (file_exists($urlFile)) 
 	{
 		require_once($urlFile);
 		return true;
 	} else 
 		{
 			return false;
 		}
 }

protected function update()
{

}

}