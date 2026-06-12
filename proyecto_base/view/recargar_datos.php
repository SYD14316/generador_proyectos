<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Almaceno la direccion del archivo actual
    $location=campo_limpiado(__FILE__,1,0,1);
  //Obtengo el dato del usuario actual
  $clave_usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0,1);
  $correo_usuario=campo_limpiado($_SESSION[UBI]['correo'],2,0,1);
  $puesto=campo_limpiado($_SESSION[UBI]['puesto'],2,0,1);
  //Defino la sentencia a ejecutar
  $sentencia="
    SELECT
      a.id,
      a.nombre,
      a.apellido,
      b.".$_SESSION['ubi'].",
      c.area,
      c.departamento,
      c.puesto
    FROM usuario AS a
      JOIN permisos AS b ON a.clave=b.clave_usuario
      JOIN puestos as c on a.clave=c.clave_usuario and c.puesto='$puesto'
    WHERE a.clave=$clave_usuario AND a.estado=0
  ";
  //ejecuto la sentencia y almaceno lo obtenido en una variable
  $resultado_busqueda_usuario=retorna_datos_central($sentencia,$location);
  if ($resultado_busqueda_usuario['rowCount'] > 0) {
    //Almaceno los datos obtenidos
    $datos_usuario = $resultado_busqueda_usuario['data'];
    // Recorrer los datos y llenar las filas
    foreach ($datos_usuario as $fila_usuario) {
      //obtengo el dato de los permisos y los separo por su identificador
      $dato_permiso=explode("||",campo_limpiado($fila_usuario[$_SESSION['ubi']],0,0,0));
      //extraigo todos los datos y creo un arreglo
      $datos =array (
        'id'=>campo_limpiado($fila_usuario['id'],1,0,1),
        'clave'=>campo_limpiado($clave_usuario,1,0,1),
        'correo'=>campo_limpiado($correo_usuario,1,0,1),
        'nombre'=>campo_limpiado($fila_usuario['nombre'],1,0,1),
        'apellido'=>campo_limpiado($fila_usuario['apellido'],1,0,1),
        'area'=>campo_limpiado($fila_usuario['area'],1,0,1),
        'departamento'=>campo_limpiado($fila_usuario['departamento'],1,0,1),
        'puesto'=>campo_limpiado($fila_usuario['puesto'],1,0,1),
        'pag_principal'=>campo_limpiado($dato_permiso[0],1),
        'permisos'=>campo_limpiado($dato_permiso[1],1),
        'nivel'=>campo_limpiado($dato_permiso[2],1),
      );
      //Asigno los datos del arreglo a la variable de sesion
      $_SESSION[UBI]=$datos;
      //Regeso al index para que mande a llamar el body
      header('Location:../index.php');
    }
  }else{
    //Defino el letrero del error
    $_SESSION['error']="
      <script>
        $(document).ready(function() {
          alert('USUARIO NO ENCONTRADO');
        });
      </script>
    ";
    //Regreso al index para imprimir el mensaje
    header('Location:../index.php');
  }
?>