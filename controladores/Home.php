<?php

include 'libs/Controlador.php';

class Home extends Controlador
{
	
	private $parametros;
	public function setParametros($parametros)
	{
		$this->parametros = $parametros;
	}
	

	function index()
	{	

		$this->cargarVista("index");
		$usuario = $this->cargarModelo("Usuario");
		$result = $usuario->getUsuarios();


		
 }
	function imprimir()
	{
		foreach ($this->parametros as $valor)
		{
		echo " ".$valor;
		}
	}
	
	function agregar(){
		

	}

	function agregarU(){
		$nombre= $_POST["nombre"];
		$user= $_POST["username"];
		$pass= $_POST["pass"];
		$pass2= $_POST["pass2"];

		if($pass == $pass2)
		{
		$modelo = $this->cargarModelo("Usuario");
		$modelo->registrarUsuario($nombre, $user, $pass);
		echo "estas registrado";
		}
		else {
			echo "La contraseña no coindice";
		}
	}
	function login(){
		$username = $_POST["username"];
		$pass = $_POST["pass"];
		$modelo = $this->cargarModelo("Usuario");
		$respuesta = $modelo->authenticate($username, $pass);
		if($respuesta != null && $respuesta->rowcount()>0)
		{
			echo "exito";
			setcookie("chsm",$username,time()+3600, "/");
			header("Location:/ChatSystem/chat.php");
			exit();
		}
		else
		{
			echo "fallido";
		}
	}
	function logout(){
		setcookie("chsm","",time() - 3600, "/");
		header("Location: /ChatSystem");
	}
}