<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Se incluye el archivo de conexión
	include_once A_CONNECTION;
	//Se obtienen los datos de los campos y se les da formato
	$id=campo_limpiado($_POST['opt_form'],2,0,0);
	
	//Se define la sentencia a ejecutar
	$sentencia="SELECT * FROM menu where id=$id";
	//Trato de ejecutar la sentencia y sino arrojo el error
	try {
		$stmt = $conn->prepare($sentencia);
		// Ejecutar la sentencia
		$stmt->execute();
		//Asocio los datos de la tabla obtenidos
		while ($tabla=$stmt->fetch(PDO::FETCH_ASSOC)) {
			//extraigo los datos del registro
			$directorio=$tabla['directorio'];
			$sub_directorio=$tabla['sub_directorio'];
			$archivo=$tabla['archivo'];
			//Revio si el direcorio esta correcto
			if (($sub_directorio==Null)||($sub_directorio=='')) {
				$sub_directorio=Null;
			}else{
				$sub_directorio=$sub_directorio."/";
			}
			//Mando a llamar al archivo
			include_once A_MODEL.$directorio.'/'.$sub_directorio.$archivo.'.php';
		}
	} catch (PDOException $e) {
		//Almaceno el error en una variabLe
		$error=$e->getMessage();
		//Ubico el archivo desde donde se presenta el error
		$archivo=__FILE__;
		//Mando a escribir el mensaje
		escribir_log($error,$sentencia,$archivo);
		//Detengo el procedimiento
		die();
	}
?>