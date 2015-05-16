<?php 

require_once 'libs/Modelo.php';

class Usuario extends Modelo
{

	function __construct()
	{
		parent::__construct();
	}

	function getUsuarios()
	{
		return $this->query("select * from users");
	}
	function registrarUsuario($nombre , $username, $pass){
	    try{
	  	
            //write query
	        /*$query = "INSERT INTO users SET id=? , nombre = ?, username = ?, pass= ?, register_date=?, last_connection=?" ;*/
	        return $this->query("INSERT INTO users (id, nombre, username, pass, register_date, last_connection) VALUES ('NULL', '$nombre', '$username', '$pass', '', '')") ;
	    
	  
	    }catch(PDOException $exception){ //to handle error
	        return  "Error: " . $exception->getMessage();
	    }
    }
    
function actualizarUsuario($id){
	    try{
	  	
            //write query
	        $query = "UPDATE users SET Id=?, Nombre=? WHERE id = $id";
	        $stmt = $conexion->prepare($query);  
	       
	         $stmt->bindParam(1, $this->id);
	         $stmt->bindParam(2, $this->nombre);
	     
	        if($stmt->execute()){
	            return true;
	        }else{
                    return "<h4>"."Ocurrio un error al actualizar el Usuario"."</h4>";
               
                
	        }
	  
	    }catch(PDOException $exception){ //to handle error
	        return  "Error: " . $exception->getMessage();
	    }
    }

function eliminarUsuario($id){
	    try{
	  	include 'libs/db_connect.php';
            //write query
	        $query = "DELETE FROM users WHERE id = $id";
	        $stmt = $conexion->prepare($query); 
	        	     
	        if($stmt->execute()){
	            return true;
	        }else{
                    return "<h4>"."Ocurrio un error al eliminar el Usuario"."</h4>";
               
                
	        }
	  
	    }catch(PDOException $exception){ //to handle error
	        return  "Error: " . $exception->getMessage();
	    }
    }

    
function search($id)
{
     include 'libs/db_connect.php';    
    
        $query = "SELECT * FROM users  WHERE id= $id  limit 1";
    
            $stmt = $conexion->prepare($query);  
	         
	        
    
            $result =  $conexion->query($query);
            return $result;
	  
    }    
function authenticate($username, $pass)
{
	return $this->query("SELECT * FROM users where username='$username' and pass='$pass' ");
}

}