<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Defino la zona horaria
	date_default_timezone_set('America/Monterrey');
	//Obtngo y asigno los datos de la DB
	$user_usr=USER_DB_USUARIO;
	$passwd_usr=PASSWRD_DB_USUARIO;
	$host_usr=HOST_DB_USUARIO;
	$dbname_usr=NAME_DB_USUARIO;
	$port_usr=PORT_DB_USUARIO;
	//Creo la cadena de conexion
	$dsn_usr="mysql:host=$host_usr;dbname=$dbname_usr;port=$port_usr";
	//Trato de realizar la conexion y sino arrojo el error
	try{
		$conn_usr = new PDO($dsn_usr,$user_usr,$passwd_usr);
		$conn_usr->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		//echo 'Conectado a '.$conn->getAttribute(PDO::ATTR_CONNECTION_STATUS);
	} catch (PDOException $e) {
        //Almaceno el error en una variabLe
        $error=$e->getMessage();
        //Defino la sentencia
        $sentencia="Conexion a base de datos";
        //Ubico el archivo desde donde se presenta el error
        $archivo=__FILE__;
        //Mando a escribir el mensaje
        escribir_log($error,$sentencia,$archivo);
        //Detengo el procedimiento
        die();
	}
?>