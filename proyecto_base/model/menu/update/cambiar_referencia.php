<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el dato del usuario actual
    $id=campo_limpiado($_SESSION[UBI]['id'],0,0,1);
    $clave=campo_limpiado($_SESSION[UBI]['clave'],0,0,1);
    $correo=campo_limpiado($_POST['correo'],0,0,1);
    $nombre=campo_limpiado($_SESSION[UBI]['nombre'],0,0,1);
    $apellido=campo_limpiado($_SESSION[UBI]['apellido'],0,0,1);
    $datos=explode("||", $_POST['puesto']);
    $area=campo_limpiado($datos[0],0,0,1);
    $departamento=campo_limpiado($datos[1],0,0,1);
    $puesto=campo_limpiado($datos[2],0,0,1);
    $pag_principal=campo_limpiado($_SESSION[UBI]['pag_principal'],0,0,1);
    $permisos=campo_limpiado($_SESSION[UBI]['permisos'],0,0,1);
    $nivel=campo_limpiado($_SESSION[UBI]['nivel'],0,0,1);
  //extraigo todos los datos y creo un arreglo
    $datos =array (
      'id'=>$id,
      'clave'=>$clave,
      'correo'=>$correo,
      'nombre'=>$nombre,
      'apellido'=>$apellido,
      'area'=>$area,
      'departamento'=>$departamento,
      'puesto'=>$puesto,
      'pag_principal'=>$pag_principal,
      'permisos'=>$permisos,
      'nivel'=>$nivel,
    );
  //Asigno los datos del arreglo a la variable de sesion
    $_SESSION[UBI]=$datos;
  //Regeso al index para que mande a llamar el body
    header('Location:../../../index.php');
  //
?>