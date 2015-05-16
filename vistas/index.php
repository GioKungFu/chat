<head>
<title>Caso Estudio Chat</title>
<link rel="stylesheet" href="/ChatSystem/vistas/css/style.css" type="text/css" rel="stylesheet">    
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="/vistas/js/main.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<h2 class="titulo">Usuarios</h2>
<div class="container contenedor">	
<section class="col-md-6">	
<form action="home/agregarU" method="POST" class="registro">
 <div class="form-group">  
<input type="text" name="nombre" placeholder="nombre" required>
    </div>
     <div class="form-group">  
<input type="text" name="username" placeholder="username" required>
    </div>
     <div class="form-group">  
<input type="password" name="pass" placeholder="pass" required>
    </div>
     <div class="form-group">  
<input type="password" name="pass2" placeholder="pass" required>
    </div>
     <div class="form-group">  
<input type="submit" name="Registrar" value="Registrar">
    </div>     
</form>
</section>
    
<?php
if (!isset($_COOKIE["chsm"])) 
{
	

?>
<aside class="col-md-6">    
<form action="/ChatSystem/home/login" method="POST" class="registro">
 <div class="form-group">    
<input type="text" name="username" placeholder="username" required>
</div>
 <div class="form-group">     
<input type="password" name="pass" placeholder="pass" required>
    </div>
     <div class="form-group"> 
<input type="submit" name="login" value="login">
    </div>
</form>
</aside>
<?php
} else {
	?>
    <div class="chat_wrapper_row">
        <div class="messaje_box col_md_12" id="messaje_box"></div>
        <div class="panel_form_group">
            <input type="hidden" name="name" id="name">
            <input type="text" name="messaje" id="messaje" placeholder="Mesaje" maxlength="80" class="form_control">
            <button id="send-btn" class="btn btn-default">enviar</button>
        </div>    
    </div>
	<a href="/ChatSystem/home/logout">cerrar sesion</a>
<?php
}
?>
<!--</as>-->

</div>
</body>