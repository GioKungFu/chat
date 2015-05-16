<!<html doctype>
<html>
<head>
	<title>sesion</title>

	<meta charset ="UTF-8">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

	

		<script type="text/javascript" src="/ChatSystem/vistas/js/main.js"></script>

		<link rel="stylesheet" href="/ChatSystem/vistas/css/style.css">
</head>

<body>
<?php

$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
            $user_colour = array_rand($colours);


?>
<a href= "/ChatSystem/home/logout"> Cerrar sesion </a>
	
	 <div class="chat_wrapper row">

 				

		   	<div class="message_box col-md-12" id="message_box"></div>
			<div class="panel form-group">
				<input type="hidden" name="name" id="name" value="<?php echo $_COOKIE['chsm'];?>" />		
				<input type="text" name="message" id="message" placeholder="Mensaje" maxlength="80" class="form-control"/>

				<button id="send-btn" class="btn btn-default" style="width:100%">Enviar </button>

  			<input type="hidden" name="color" id="color" value="<?php echo $colours[$user_colour]; ?>"  />

			</div>

			
		 </div>  

</body>
</html>
