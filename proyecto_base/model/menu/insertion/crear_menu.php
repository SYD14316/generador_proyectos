<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el id del usuario actual
    $clave_usr=campo_limpiado($_SESSION[UBI]['clave'],2,0,1);
  //Se obtienen los datos de los campos y se les da formato
    $nombre_grupo=campo_limpiado($_POST['nombre_grupo'],0,0,1);
    $nombre_menu=campo_limpiado($_POST['nombre_menu'],0,0,1);
    $directorio=campo_limpiado($_POST['directorio'],0,0,1);
    $sub_directorio=campo_limpiado($_POST['sub_directorio'],0,0,0);
    $archivo=campo_limpiado($_POST['archivo'],0,0,1);
    $descripcion=campo_limpiado($_POST['descripcion'],0,0,0);
  //Reviso si el campo de subdirectorio esta vacio o si se definio algun valor
    if (($sub_directorio==Null)||($sub_directorio=="")) {
      $sub_directorio="Null";
    }else{
      $sub_directorio="'$sub_directorio'";
    }
  //Defino la sentencia de busqueda
    $sentencia="
      SELECT count(id) as exist FROM menu WHERE nombre_grupo='$nombre_grupo'  and nombre_menu='$nombre_menu' and directorio='$directorio' and archivo='$archivo' and descripcion='$descripcion';
    ";
  //Ejecuto la sentencia
    $devuelto=busca_existencia($sentencia);
  //Evaluo el resultado obtenido
    if ($devuelto==0) {
      //Defino la sentencia de insercion
        $sentencia="
          INSERT INTO menu (nombre_grupo,nombre_menu,directorio,sub_directorio,archivo,descripcion) VALUES ('$nombre_grupo','$nombre_menu','$directorio',$sub_directorio,'$archivo','$descripcion');
          INSERT INTO bitacora (fecha,hora,accion,usuario) VALUES ( '".ahora(1)."','".ahora(2)."','CREACIÓN DEL MENÚ $nombre_menu EN EL GRUPO $nombre_grupo',$clave_usr);
        ";
      //Ejecuto la sentencia
        $resultado=ejecuta_sentencia_sistema($sentencia,true);
      //Evaluo el resultado obtenido
        if ($resultado==True) {
          echo "
            <script>
              alert('¡AÑADIDO!, MENÚ AGREGADO');
              registrar_menu();
            </script>
          ";
        }
      //
    }else{
      echo "<script>alert('ESTE MENU YA SE ENCUENTRA REGISTRADO');</script>";
    }
  //
?>