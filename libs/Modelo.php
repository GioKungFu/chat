<?php 

class Modelo
{

private $host = 'mysql.hostinger.co';
private $db_name = 'u417838976_chat';
private $username = 'u417838976_root';
private $password = '123456';

 private $nombreTabla;

private $conexion;
function __construct()
{
	try 
	{
		$this->conexion = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
	} 
		catch (PDOException $exception) 
		{
			echo "connection error: ".$exception->getMessage();
		}
}


protected function query($query)
{
	return $this->conexion->query($query);
}

}