<?php
//servidor donde correra el web socket
$host = 'localhost';
//puerto
$port = '9000'; 
$null = NULL; 

//Se crea un web socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//define que el puerto sea reutilizable para varias conexiones es decir varios usuarios.
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//hace que el $socket escuche el puerto 9000
// Y que busque conecciones entrantes en $socket
socket_bind($socket, 0, $port);
socket_listen($socket);

//agrega el $socket, el socket que escucha conexiones
// a la lista de clientes.
$clients = array($socket);

//inicia un ciclo infinito
while (true) {
	//duplica el arreglo clientes, para poder hacer
        //multiples conexiones.
	$changed = $clients;
	//indica cual socket cambia su estado
        //para este caso solo vamos a leer datos 
        //de los SOCKET RESOURCE en $Changed.
        //$changed es pasado como referencia
	socket_select($changed, $null, $null, 0, 10);
	
	//verifica si algun socket cambio su estado
        if (in_array($socket, $changed)) {
                //ya que $socket es el encargado de escuchar conecciones
                //si $socket cambia, se debe aceptar una coneccion al server y
                // crear un socket nuevo.
		$socket_new = socket_accept($socket); 
                //luego agrega ese socket nuevo a la lista de clientes.
		$clients[] = $socket_new; 
                
		//ahora simplemente leemos la informacion inicial, enviada 
                //por el socket que se acaba de conectar, esta informacion
                //es por asi decirlo un saludo para nuestro servidor.
		$header = socket_read($socket_new, 1024); 
                //Ahora es momento que el saludo enviado por el socket
                //sea respondido por el servidor, para asi saber que la 
                //conexion fue exitosa.
		perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
		/*
                 * Ahroa se obtiene la IP de ese cliente que se
                 * conecto
                 */
		socket_getpeername($socket_new, $ip); //get ip address of connected socket
                //Ahora lo que se hace es crear un mensaje, para comunicarlo 
                // a todos los usuarios, que un usuario nuevo se conecto.
                // primero se convierte en un json, y luego se codifica con mask.
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected')));
                //finalmente el mensaje es enviado a todos
		send_message($response); 
		
		/**
                 * Ya que fue atendido este cliente, 
                 * lo eliminamos de la lista de clientes cambiados
                 * para no atenderlo de nuevo en otra ocacion.
                 */
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}
	
	/*
         * Ahora debemos estar pendientes
         * de que los usuarios conectados 
         * nos envien datos, para ello debemos iterar sobre todos
         * los sockets que presentan cambios ya sea para leer o escribir.
         */
	foreach ($changed as $changed_socket) {	
		
		/*
                 * En este caso para el socket actual,
                 * se obtienen los bytes que este escribiendo 
                 * y estos se almacenan en $buf.
                 * Para este caso se leen 1024 bytes
                 */
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
                        /*
                         * unmask es un metodo para obtener datos,
                         * sobre la logitud del mensaje. y obtener el
                         * texto adecuadamente ya que no se sabe
                         * que texto puede enviar el usuario.
                         */
			$received_text = unmask($buf); 
                        /*
                         * Lo que sabemos es que viene en formato json
                         * asi que lo decodificamos.
                         */
			$tst_msg = json_decode($received_text); 
                        /*
                         * Luego obtenemos los datos del usuario.
                         */
			$user_name = $tst_msg->name; 
			$user_message = $tst_msg->message;
			$user_color = $tst_msg->color; 
			
			//Finalmente prepara el mensaje
                        //para enviarselos a todos
                        //los usuarios conectados al chat.
			$response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
                        //enviamos el mensaje
			send_message($response_text); //send data
                        //se sale del while  y del foreach
			break 2;
		}
		/*
                 * Si el socket cambio pero no escribio nada,
                 * entonces se desconecto.
                 */
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { 
			/*Elimina el cliente*/
			$found_socket = array_search($changed_socket, $clients);
                        /*
                         * Obtiene el socket basados en la IP.
                         */
			socket_getpeername($changed_socket, $ip);
                        //lo elimina de la lista de clientes.
			unset($clients[$found_socket]);
			
			//notifica a todos la desconeccion del cliente
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}
}
// cierra el sockt
socket_close($sock);
/*
 * Funcion que envia los mensajes a todos los
 * sockets conectados, o en espera de mensajes.
 */
function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}


/*
 * Desenmascara los datos enviados por los usuarios
 */
function unmask($text) {
        //obtiene el valor ascii de un caracter y lo 
	$length = ord($text[1]) & 127;//convierte el ascci en numero
	if($length == 126) {
            //si la longitud del mensaje es de 126 obtiene
            //las mascaras y los datos por separado
		$masks = substr($text, 4, 4);                
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
        
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
            //operacion byte a byte para desenmascarar y converitr en texto.
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Codifica una cabecera para el texto que se va a enviar
//ya que los textos son enviados como bytes a traves de 
//los sockets entonces se hace necesario saber
//que longintud tiene el mensaje, y con el metodo pack, lo que se 
//hacer es enmascarar ese mensaje al inicio del texto.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//se responde el saludo a un cliente.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{       
	$headers = array();
        //se separa el mensaje por medio de lineas 
	$lines = preg_split("/\r\n/", $receved_header);
        /**
         * Por cada linea se seleccionan y se separan los datos.
         */
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}
        /* Para que el cliente sepa que hubo una conexion correcta
         * El servidor debe Obtener una llava enviada por el cliente
         * en este caso es 'Sec-WebSocket-Key' y agregarle un string.
         * Luego de eso el debe crear un mensaje para el cliente,
         * como se puede ver en la parte baja.
         */
	$secKey = $headers['Sec-WebSocket-Key'];
	
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	/*
         * Lo que aqui se ve es a cabecera de un mensaje enviado  via HTTP
         */
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/deamon.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        /**
         * Se envia la respuesta al cliente. de que la conexion 
         * fue exitosa.
         */
	socket_write($client_conn,$upgrade,strlen($upgrade));
}