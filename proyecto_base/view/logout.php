<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
		if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Se manda a llamar el archivo de configuración
		include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	//Almaceno la direccion del archivo actual
		$location=campo_limpiado(__FILE__,1,0,1);
	//Arreglo de campos
		$campos=array("accion");
	//Arreglo de datos
		$datos=array("CIERRE DE SESION");
	//Se realiza el registro de bitacora
		registra_bitacora($campos,$datos,$location);
	//Destruyo la sesion y sus datos
		session_destroy();
	//Redirijo la pagina a la de intranet
		header("Location:https://intranet.marista.mx");
	//
?>