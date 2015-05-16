$(document).ready(function() {

var websocket= new WebSocket("ws://localhost:9000/deamon.php");

websocket.onopen= function (evt)
{
	$('#message_box').append("<div class=\"system_msg\">Conectado al servidor!</div>");
};

websocket.onclose= function (evt)
{
	$('#message_box').append("<div class=\"system_msg\"> Conexión cerrada</div>");
};

websocket.onmessage= function (evt)
{
	var msg= JSON.parse(evt.data);
	var type= msg.type;
	var umsg= msg.message;
	var uname=msg.name;
	var ucolor= msg.color;

	if (type=='usermsg') 
		{
			$('#message_box').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+
				uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
		}

	if (type=='system') 
	{
		$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
	}

	$('#message').val('');	

};

websocket.onerror= function (evt)
{
	$('#message_box').append("<div class=\"system_msg\">Error - "+ev.data+"</div>");
};


$('#send-btn').click(function(){

		var mensaje= $('#message').val();
		var nombre= $('#name').val();

		if (nombre=="") 
			{
				alert("Ingrese un nombre");
				return;
			};

		if (mensaje=="") 
		{
			alert("Ingrese un mensaje");
			return;
		};	
			
		var msg= {
			message: mensaje,
			name: nombre,
			color: $('#color').val()
		};

		websocket.send(JSON.stringify(msg));	

	});

}
    
 );

//websocket.send(message);
//websocket.close();