<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el archivo obtenido
    $archivo=campo_limpiado($_POST['log'],2,0,1);
    $ruta_archivo =A_LOGS."$archivo";
  //Reviso si el archivo existe
    if(file_exists("$ruta_archivo")){
      //Elimino el archivo
      unlink("$ruta_archivo");
      echo "<script>alert('ARCHIVO ELIMINADO');</script>";
    }else{
      //Imprimo un mensaje
      echo "<script>alert('ARCHIVO NO ENCONTRADO');</script>";
      //Detengo la ejecución
      die();
    }
  //
?>