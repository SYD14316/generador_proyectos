<?php
	// ╔════════════════════════════════════════════════════════╗
	// ║                 FUNCIONES DE LIMPIEZA                  ║
	// ╠════════════════════════════════════════════════════════╣
	// ║    PROGRAMADO POR: ING. OSCAR ALEJANDRO RUIZ GARCÍA    ║
	// ║             VERSIÓN 1.0   /   FEBRERO 2022             ║
	// ╚════════════════════════════════════════════════════════╝
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
	require_once A_LIB."purifier/HTMLPurifier.auto.php";
	/*require_once $_SERVER['DOCUMENT_ROOT'].'/proyecto_base/lib/purifier/HTMLPurifier.auto.php';*/
	
	function limpiar_campo($dirty){
		unset($config);
		unset($purifier);
		unset($clean);
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', '');
		$purifier = new HTMLPurifier($config);
		$clean = $purifier->purify($dirty);
		$clean = str_replace("'", "’", $clean);
		return $clean;
	}

?>