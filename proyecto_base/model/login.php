<?php
  //Defino el apuntador de la carpeta actual
    define('TARGET', 'proyecto_base');
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Asigno la variable de sesion del apuntador definido
    $_SESSION['ubi']=TARGET;
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.TARGET.'/lib/config.php';
  //Almaceno la direccion del archivo actual
    $location=campo_limpiado(__FILE__,1,0,1);
  //Obtengo el dato enviado
    $usuario = campo_limpiado($_POST['resultadoingreso'],2);
    $tipoingresousado = campo_limpiado($_POST['tipoingresousado']);
  //identifico la erferencia del correo sea google o microsoft
    if ($tipoingresousado=='Google') {
      $correo=campo_limpiado($_POST['resultadoingreso'],2)."@g.".campo_limpiado($_POST['dominio'],2).".edu.mx";
    }else{
      $correo=campo_limpiado($_POST['resultadoingreso'],2)."@".campo_limpiado($_POST['dominio'],2).".edu.mx";
    }
  // Se verifica que el campo no este vacio
    if (empty($usuario)) {
      $_SESSION['error']="
        <script>
          $(document).ready(function() {
            alert('Usuario no encontrado');
          });
        </script>
      ";
      header('Location:../index.php');
    } else {
      //Elimino los espacios en el correo
      str_replace(" ", "", $usuario);
      //Defino la sentencia de busqueda
      $sentencia="SELECT clave_usuario FROM correos WHERE correo = '$correo'";
      //Realizo la busqueda del correo
      $resultado_busqueda_correo = retorna_datos_central($sentencia,$location);
      //Si la cantidad de filas obtenidas es mayor a 0
      if ($resultado_busqueda_correo['rowCount'] > 0) {
        //Almaceno los datos obtenidos
        $datos_busqueda = $resultado_busqueda_correo['data'];
        //Mientras el resultado no este vacio
        if (!empty($datos_busqueda)) {
          // Recorrer los datos y llenar las filas
          foreach ($datos_busqueda as $fila_busqueda) {
            //Almaceno la calve de usuario en donde le corresponde
            $clave_usuario=campo_limpiado($fila_busqueda['clave_usuario'],0,1,1);
            //Defino la sentencia de obtencion de datos
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
                JOIN puestos as c on a.clave=c.clave_usuario
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

                if ($dato_permiso[2]==0) {
                  header("Location:https://intranet.marista.mx");
                  //header("Location:http://localhost");
                }else{
                  //Exxtraigo todos los datos y creo un arreglo
                    $datos =array (
                      'id'=>campo_limpiado($fila_usuario['id'],1,0,1),
                      'clave'=>campo_limpiado($clave_usuario,1,0,1),
                      'correo'=>campo_limpiado($correo,1,0,1),
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
                  //arreglo de campos
                    $campos=array("accion");
                  //arreglo de datos
                    $datos=array("INICIO DE SESION");
                  //Se realiza el registro de bitacora
                    registra_bitacora($campos,$datos,$location);
                  //Regeso al index para que mande a llamar el body
                    header('Location:../index.php');
                  //
                }
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
              header("Location:https://intranet.marista.mx");
              //header("Location:http://localhost");
            }
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
          header("Location:https://intranet.marista.mx");
          //header("Location:http://localhost");
        }
      }
    }
  //
?>