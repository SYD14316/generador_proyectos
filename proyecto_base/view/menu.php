<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Almaceno la direccion del archivo actual
    $location=campo_limpiado(__FILE__,1,0,1);
  //Asigno los datos de sesion a variables especificas
  $clave=campo_limpiado($_SESSION[UBI]['clave'],2,0,1);
  $correo=campo_limpiado($_SESSION[UBI]['correo'],2,0,1);
  $nombre=campo_limpiado($_SESSION[UBI]['nombre'],2,0,1);
  $apellido=campo_limpiado($_SESSION[UBI]['apellido'],2,0,1);
  $permisos=campo_limpiado($_SESSION[UBI]['permisos'],2,0,1);
  $departamento=campo_limpiado($_SESSION[UBI]['departamento'],2,0,1);
  $puesto=campo_limpiado($_SESSION[UBI]['puesto'],2,0,1);
  //Divido los menu's por su separador
  $menus=explode("!!", $permisos);
  //Defino character para utilizar en una condicional
  $condicional="(";
?>
<body>
  <div class="sticky-top">
    <nav class="navbar navbar-expand-lg justify-content-center success">
      <a class="navbar-brand" href="index.php"><img src="img/Logotipo_Maristas.png" style="height: 40px; background: white;"></a>
      <a class="btn text-light" href="https://intranet.marista.mx"><i class="fa fa-home" style="font-size: 40px;"></i></a>
      <a class="btn text-light" target="_blank" href="files/manual.pdf"><i class="fas fa-question-circle" style="font-size: 40px;"></i></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" style="background-color: #ffb700;">
        <span class="navbar-toggler-icon"><strong>+</strong></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav d-flex flex-wrap">
          <li class="nav-item dropdown">
            <a class="nav-link btn btn-outline-dark text-light dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
              <?php 
                //Imprimo el nombre y el puesto de la persona
                echo "
                  <strong>$clave - $nombre $apellido</strong>
                  <br>
                  <small>$puesto - $correo</small>
                "; 
              ?>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="view/recargar_datos.php">
                <strong>
                  <i class="fas fa-sync" ></i>
                  Recargar datos
                </strong>
              </a>
              <div class="dropdown-divider"></div>
                <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_cambiar_referencia" id="frm_cambiar_referencia">
                  <div class="input-group mb-3 input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><strong>PUESTO</strong></span>
                    </div>
                    <select  name="puesto" id="puesto" class="custom-select" required="">
                      <?php
                        echo "<option value='".campo_limpiado($area,1)."||".campo_limpiado($departamento,1)."||".campo_limpiado($puesto,1)."'>".$puesto."</option>";
                        //Defino la sentencia de busqueda
                        $sentencia="SELECT * FROM puestos where clave_usuario=$clave and puesto<>'$puesto'";
                        //Realizo la busqueda del puesto
                        $resultado_busqueda_puesto = retorna_datos_central($sentencia,$location);
                        //Si la cantidad de filas obtenidas es mayor a 0
                        if ($resultado_busqueda_puesto['rowCount'] > 0) {
                          //Almaceno los datos obtenidos
                          $datos_busqueda = $resultado_busqueda_puesto['data'];
                          //Mientras el resultado no este vacio
                          if (!empty($datos_busqueda)) {
                            // Recorrer los datos y llenar las filas
                            foreach ($datos_busqueda as $fila_busqueda) {
                              echo "<option value='".campo_limpiado($fila_busqueda['area'],1)."||".campo_limpiado($fila_busqueda['departamento'],1)."||".campo_limpiado($fila_busqueda['puesto'],1)."'>".$fila_busqueda['puesto']."</option>";
                            }
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="input-group mb-3 input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><strong>CORREO</strong></span>
                    </div>
                    <select  name="correo" id="correo" class="custom-select" required="">
                      <?php
                        echo "<option value='".campo_limpiado($correo,1)."'>".$correo."</option>";
                        //Defino la sentencia de busqueda
                        $sentencia="SELECT * FROM correos where clave_usuario=$clave and correo<>'$correo'";
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
                              echo "<option value='".campo_limpiado($fila_busqueda['correo'],1)."'>".$fila_busqueda['correo']."</option>";
                            }
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="CAMBIAR" class="btn btn-success btn-block" onclick="cambiar_referencia();"/>
                  </div>
                  <div class="form-group">
                    <div id="respuesta_cambiar_referencia"></div>
                  </div>
                </form>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="view/logout.php">
                <strong>
                  <i class="fas fa-sign-out-alt" ></i>
                  Cerrar sesión
                </strong>
              </a>
            </div>
          </li>
          <?php
            //Verifico si los campos de permisos no estan vacios
            if ($permisos!=Null) {
              //Si los campos de menu son mayores a uno
              if (count($menus)>1) {
                for ($i=0; $i < count($menus) ; $i++) {
                  //Creo el condicional del if para obtener los nombres de los campos
                  $condicional=$condicional."id=".$menus[$i]." or ";
                }
                //Le extraigo el ultimo or que esta de mas
                $condicional=substr($condicional, 0, -4);
                //Le agrego un parentesis de cierre
                $condicional=$condicional.")";
              //Si no es mayor de uno
              }else{
                //Creo un condicional sencillo
                $condicional="(id=".$permisos.")";
              }
              //Defino la sentencia de busqueda
              $sentencia="SELECT nombre_grupo FROM menu where $condicional group by nombre_grupo";
              //Realizo la busqueda del correo
              $resultado_busqueda_grupo = retorna_datos_sistema($sentencia,$location);
              //Si la cantidad de filas obtenidas es mayor a 0
              if ($resultado_busqueda_grupo['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                $datos_busqueda_grupo = $resultado_busqueda_grupo['data'];
                //Mientras el resultado no este vacio
                if (!empty($datos_busqueda_grupo)) {
                  // Recorrer los datos y llenar las filas
                  foreach ($datos_busqueda_grupo as $fila_busqueda_grupo) {
                    $nombre_grupo=$fila_busqueda_grupo['nombre_grupo']; ?>
                    <li class="nav-item dropdown">
                      <a class="nav-link btn btn-outline text-light dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        <strong><?php echo $nombre_grupo ?></strong>
                      </a>
                      <div class="dropdown-menu">
                        <?php
                          //Defino la sentencia de busqueda
                          $sentencia="SELECT * FROM menu where $condicional and nombre_grupo='$nombre_grupo'";
                          //Realizo la busqueda del puesto
                          $resultado_busqueda_opcion = retorna_datos_sistema($sentencia,$location);
                          //Si la cantidad de filas obtenidas es mayor a 0
                          if ($resultado_busqueda_opcion['rowCount'] > 0) {
                            //Almaceno los datos obtenidos
                            $datos_busqueda_opcion = $resultado_busqueda_opcion['data'];
                            //Mientras el resultado no este vacio
                            if (!empty($datos_busqueda_opcion)) {
                              // Recorrer los datos y llenar las filas
                              foreach ($datos_busqueda_opcion as $fila_busqueda_opcion) { ?>
                                <a class="dropdown-item" data-toggle="tooltip" title="<?php echo $fila_busqueda_opcion['descripcion'] ?>" onclick="menu('<?php echo campo_limpiado($fila_busqueda_opcion['id'],1,0,1) ?>')">
                                  <strong>
                                    <i class="fas fa-chevron-circle-right"></i>
                                    <?php echo $fila_busqueda_opcion['nombre_menu'] ?>
                                  </strong>
                                </a><?php
                              }
                            }
                          }
                        ?>
                      </div>
                    </li><?php
                  }
                }
              }
            }
          ?>
        </ul>
      </div>
    </nav>
  </div>
  <script>
    $(function cambiar_referencia(){
      $("#frm_cambiar_referencia").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_cambiar_referencia"));
        formData.append("dato", "valor");
        $.ajax({
          url: "model/menu/update/cambiar_referencia.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){ $("#respuesta_cambiar_referencia").html("<div class='spinner-border'></div>"); },
        })
        .done(function(res){ $("#respuesta_cambiar_referencia").html(res); });
      });
    });
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>