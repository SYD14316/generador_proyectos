<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo al usuario actual
    $clave_usr=campo_limpiado($_SESSION[UBI]['id'],2,0,1);
  //obtengo el identificador del menu a agregar
    $id_permiso=campo_limpiado($_POST['id'],2,0,1);
  //Obtengo la clave del colaborador a editar
    $clave=campo_limpiado($_POST['clave'],2,0,1);
  //Defino la sentencia para descarga de los permisos que tiene el usuario
    $sentencia = "SELECT ".$_SESSION['ubi']." FROM permisos WHERE clave_usuario='$clave'";
  //Ejecuto la sentenci ay obtengo el resultado
    $resultado_busqueda_correo = retorna_datos_central($sentencia);
  //Si la cantidad de filas obtenidas es mayor a 0
    if ($resultado_busqueda_correo['rowCount'] > 0) {
      //Almaceno los datos obtenidos
        $datos_busqueda = $resultado_busqueda_correo['data'];
      //Mientras el resultado no este vacio
        if (!empty($datos_busqueda)) {
          // Recorrer los datos y llenar las filas
            foreach ($datos_busqueda as $registro) {
              //Se creea un array separando los datos de pag. principal, permios y nivel de acces por su identificador
                $dvision_permisos=explode("||", $registro[$_SESSION['ubi']]);
                $pag_principal=$dvision_permisos[0];
                $permisos_actuales=$dvision_permisos[1];
                $nivel_acceso=$dvision_permisos[2];
              //Separo los permisos en un array para revizarlo
                $datos_permisos=explode("!!", $permisos_actuales);
              //Obtengo la cantidad de registros en el arreglo
                $cantidad=count($datos_permisos);
              //Defino un variable en vacio
               $permisos=Null;
              //Reviso el valor con una condicional
                if ($cantidad==1) {
                  $resultado=$pag_principal."||||".$nivel_acceso;
                }else{
                  //Creeo un ciclo para recorrer el arreglo
                  for ($i=0; $i < $cantidad; $i++) { 
                    //Reviso si el arreglo de los permisos en esa posicion, corresponde con el valor buscado
                    if ($id_permiso!=$datos_permisos[$i]) {
                      //Creo un concatenado con los permisos
                      $permisos=$permisos.$datos_permisos[$i]."!!";
                    }
                  }
                }
              //Le quipo el ultimo par de identificadores que esta de mas
                $permisos=substr($permisos, 0, -2);
              //Se concatenan los datos completos
                $resultado=$pag_principal."||".$permisos."||".$nivel_acceso;
              //Defino la sentencia para actualizar al colaborador
                $sentencia="
                  UPDATE permisos set ".$_SESSION['ubi']."='$resultado' where clave_usuario='$clave';
                  INSERT INTO bitacora (fecha,hora,accion,usuario) VALUES ('".ahora(1)."','".ahora(2)."','SE LE RETIRA EL MENÚ N° $id_permiso AL USUARIO CLAVE $clave',$clave_usr);
                ";
              //Ejecuto la actualizacion de los menu's
                $devuelto=ejecuta_sentencia_central($sentencia,true);
                if ($devuelto==true) {
                  echo "
                    <script>
                      tabla_menus_asignados();
                      tabla_menus_disponibles();
                    </script>
                  ";
                }
              //
            }
          //
        }else{
          //Defino el letrero del error
          Echo "
            <script>
              alert('EL USUARIO NO TIENE ALMACENAMIENTO DE MENU'S);
            </script>
          ";
          die();
        }
      //
    }
  //
?>