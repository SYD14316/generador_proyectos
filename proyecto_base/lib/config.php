<?php
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	//Constante del sistema a usar
		if ($_SESSION['ubi']=='') { $_SESSION['ubi']="proyecto_base"; }
		define('UBI', $_SESSION['ubi'] );
	// Constantes para la funcion de envio de coreo
		define( 'MAIL_SEND', 'intranet@marista.mx' );
		define( 'PASS_SEND', ',[BTtT.(bj6+^4D}icYBb5iae(D26u)(^cMQ' );
		define( 'PORT_SEND', 465 );
		define( 'HOST_SEND', 'mail.marista.mx' );
	//Direccionamiento a directorios y archivo de BDD
		define( 'A_RAIZ', $_SERVER['DOCUMENT_ROOT'].'/'.UBI.'/' );
		define( 'A_LIB', A_RAIZ.'lib/' );
		define( 'A_JS', A_RAIZ.'js/' );
		define( 'A_MODEL', A_RAIZ.'model/' );
		define( 'A_DOCS', A_RAIZ.'docs/' );
		define( 'A_LOGS', A_RAIZ.'logs/' );
		define( 'A_DOCS_V', 'docs/' );
		define( 'A_VIEW', A_RAIZ.'view/' );
		define( 'A_HEAD_IMPRESION', A_VIEW.'impresion.php' );
		define( 'A_CSS', A_RAIZ.'css/' );
		define( 'A_IMG', A_RAIZ.'img/' );
		define( 'A_RESTORE', A_RAIZ.'restore/' );
		define( 'A_IMG_V', 'img/' );
		define( 'A_CONNECTION', A_LIB.'connection.sql.db.php' );
		define( 'A_CONNECTION_USUARIO', A_LIB.'connection_usuario.sql.db.php' );
		define( 'A_TITULO', 'proyecto_base DE SERVICIO');
	//Defino el token de telegram
		define( 'TOKEN_TELEGRAM', '8481232906:AAEe_PacCPoiZD5KgnkVfwIkYniZNgR5gLM' );
	//Defino constantes de imagenes
		define( 'LOGO', 'img/Logotipo horizontal maristas con (R) Negro.png' );
	//Constantes para conexion a base de datos central de usuarios (EN SANDBOX)
		define('USER_DB_USUARIO','root');
		define('PASSWRD_DB_USUARIO','');
		define('HOST_DB_USUARIO','localhost');
		define('NAME_DB_USUARIO','maristamx_directorio23');
		define('PORT_DB_USUARIO','3306');
	//Constantes para conexion a base de datos de la plataforma (EN SANDBOX)
		define('USER_DB','root');
		define('PASSWRD_DB','');
		define('HOST_DB','localhost');
		define('NAME_DB','maristamx_proyecto_base2023');
		define('PORT_DB','3306');
	/*//Constantes para conexion a base de datos central de usuario (EN PRODUCCION)
		define('USER_DB_USUARIO','maristamx_directorio');
		define('PASSWRD_DB_USUARIO','Qtfj@oN=NB}KjquRw?2!R4=}sz[?*,$FASB5');
		define('HOST_DB_USUARIO','intranet.marista.mx');
		define('NAME_DB_USUARIO','maristamx_directorio23');
		define('PORT_DB_USUARIO','3306');
	//Constantes para conexion a base de datos de ls plataforma (EN PRODUCCION)
		define('USER_DB','maristamx_proyecto_base');
		define('PASSWRD_DB','E*c7D;@m.F3auOT&vi?pjr3{7Q#Gz-iGcwwQ');
		define('HOST_DB','intranet.marista.mx');
		define('NAME_DB','maristamx_proyecto_base2023');
		define('PORT_DB','3306');*/
	//Librerias de PHPMailer
    	require A_LIB.'PHPMailer/PHPMailer.php';
		require A_LIB.'PHPMailer/Exception.php';
		require A_LIB.'PHPMailer/SMTP.php';
	//Librerias de encriptado y limpieza
		include_once A_LIB.'self/self_lmpz.php';
    	include_once A_LIB.'self/self_form_sender.php';
    	include_once A_LIB.'self/self_ncrptcn.php';
    //Archivo de funciones varias
    	include_once A_LIB.'funciones.php';
	//
?>