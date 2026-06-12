<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el dato del usuario consultado
    $clave=campo_limpiado($_POST['clave'],2,0,1);
  //
?>
<div class="card">
  <div class="card-header"><h5>MENÚS ASIGNADOS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla_menus_asignados">
      <thead>
        <tr>
          <th></th>
          <th>GRUPO</th>
          <th>MENÚ</th>
          <th>DESCRIPCIÓN</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia para extraccion de los permisos en la base centralizada
            $sentencia = "SELECT ".$_SESSION['ubi']." FROM permisos WHERE clave_usuario='$clave'";
          //ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_busqueda_busqueda=retorna_datos_central($sentencia);
            if ($resultado_busqueda_busqueda['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $datos_busqueda = $resultado_busqueda_busqueda['data'];
              // Recorrer los datos y llenar las filas
                foreach ($datos_busqueda as $fila_busqueda) {
                  //Tomo los permisos y los separo de la pagina principal y el nivel
                    $dvision_permisos=explode("||", $fila_busqueda[$_SESSION['ubi']]);
                  //Almaceno los menus del usuario en una variable
                    $menus=explode("!!", $dvision_permisos[1]);
                  //Defino una variable condicional vacia
                    $condicional=Null;
                  //Evaluo si la condicional no es nula
                    if ($dvision_permisos[1]!=Null) {
                      //Defino el inicio de la condicional
                        $condicional=" where ";
                      //si los campos de menu son mayores a uno
                        if (count($menus)>1) {
                          for ($i=0; $i < count($menus) ; $i++) {
                            //creo el condicional del if para obtener los nombres de los campos
                              $condicional=$condicional."id=".$menus[$i]." or ";
                            //
                          }
                          //le extraigo el ultimo or que esta de mas
                            $condicional=substr($condicional, 0, -4);
                          //Le agrego un parentesis de cierre
                            $condicional=$condicional;
                          //
                        }else{
                          //creo un condicional sencillo
                            $condicional=" where id=".$dvision_permisos[1];
                          //
                        }
                      //Defino la sentencia para buscar los menu's no incluidos
                        $sentencia="SELECT * FROM menu".$condicional;
                      //ejecuto la sentencia y almaceno lo obtenido en una variable
                        $resultado_busqueda_menu=retorna_datos_sistema($sentencia);
                      //Verifico que el resultado sea mayor a 1
                        if ($resultado_busqueda_menu['rowCount'] > 0) {
                          //Almaceno los datos obtenidos
                            $datos_menu = $resultado_busqueda_menu['data'];
                          // Recorrer los datos y llenar las filas
                            foreach ($datos_menu as $fila_menu) {
                              //extraigo los datos a variables
                                $id=$fila_menu['id'];
                                $nombre_grupo=$fila_menu['nombre_grupo'];
                                $nombre_menu=$fila_menu['nombre_menu'];
                                $descripcion=$fila_menu['descripcion'];
                                $dato="'".campo_limpiado($id,1,0,1)."'";
                              //Impresion de los datos principales
                                echo "
                                  <tr>
                                    <td>
                                      <a onclick=\"retirar($dato)\" class=\"btn btn-danger text-light\"><i class=\"far fa-caret-square-left\" style=\"font-size:24px\"></i></a>
                                    </td>
                                    <td>$nombre_grupo</td>
                                    <td>$nombre_menu</td>
                                    <td>$descripcion</td>
                                  </tr>
                                ";
                              //
                            }
                          //
                        }
                      //
                    }
                  //
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  //funcion para retirar un permiso al usuario
  function retirar(opcion){
    var clave=$("#usr").val();
    var url="model/menu/update/retirar_permiso.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{
        id:opcion,
        clave:clave
      },
      success: function(datos){$('#respuesta').html(datos);}
    });
  }
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla_menus_asignados').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[],
        },
      } );
      table.on( 'responsive-resize', function ( e, datatable, columns ) {
        var count = columns.reduce( function (a,b) {
          return b === false ? a+1 : a;
        }, 0 );
        console.log( count +' column(s) are hidden' );
      } );
    } );
  //
</script>