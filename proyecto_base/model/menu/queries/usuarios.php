<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el apuntador para definir las funciones a incluir
    $opcion=campo_limpiado($_POST['opcion'],0,0,1);
  //En base a la opcion obtenida se definen las funciones
    if ($opcion==1) {
      echo "
        <script type='text/javascript'>
          function editar_permisos_menu(opcion){
            var url='model/menu/forms/editar_permisos_menu.php'
              $.ajax({
                type: 'POST',
                url:url,
                data:{ clave:opcion },
                beforeSend: function(){
                  $('#edicion').html('<div class=\"spinner-border\"></div>');
                },
                success: function(datos){ $('#edicion').html(datos); }
              });
            }
        </script>
      ";
    }else{
      echo "
        <script type='text/javascript'>
          function editar_permisos_menu(opcion){
            var url='model/menu/forms/editar_pagina_inicial.php'
              $.ajax({
                type: 'POST',
                url:url,
                data:{ clave:opcion },
                beforeSend: function(){
                  $('#edicion').html('<div class=\"spinner-border\"></div>');
                },
                success: function(datos){ $('#edicion').html(datos); }
              });
            }
        </script>
      ";
    }
  //
?>

<div class="card">
  <div class="card-header"><h5>COLABORADORES REGISTRADOS</h5></div>
  <div class="card-body">
    <div id="respuesta"></div>
    <table class="table table-bordered table-sm table-striped table-hover" id="tabla_colaborador">
      <thead>
        <tr>
          <th>CLAVE</th>
          <th>NOMBRE</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Preparo la sentencia a ejecutar
            $sentencia="
              SELECT 
                usuario.clave, 
                concat(usuario.nombre,' ',usuario.apellido) AS nombre_usuario, 
                permisos.".$_SESSION['ubi']." 
              FROM 
                usuario AS usuario 
              JOIN permisos AS permisos ON 
                permisos.clave_usuario=usuario.clave
              WHERE usuario.estado=0;
            ";
          //ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_busqueda_busqueda=retorna_datos_central($sentencia);
            if ($resultado_busqueda_busqueda['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $datos_busqueda = $resultado_busqueda_busqueda['data'];
              // Recorrer los datos y llenar las filas
                foreach ($datos_busqueda as $fila_busqueda) {
                  //extraigo los datos a variables
                    $clave=$fila_busqueda['clave'];
                    $nombre_usuario=$fila_busqueda['nombre_usuario'];
                  //Obtengo los permisos, creeo un arreglo y parto los permisos
                    $string_permisos=$fila_busqueda[$_SESSION['ubi']];

                    $dvision_permisos=explode("||", $string_permisos);
                    $nivel=$dvision_permisos[2];
                  //Defino el dato a enviar
                    $dato="'".campo_limpiado($clave,1,0,1)."'";
                  //Evaluo el nivel y defino botones especificon
                    if ($nivel==1) {
                      $boton_nivel="
                        <a onclick=\"degradar($dato)\" class=\"btn btn-danger text-light\"><i class=\"far fa-arrow-alt-circle-down\" style=\"font-size:24px\"></i></a>
                      ";
                    }else{
                      $boton_nivel="
                        <a onclick=\"ascender($dato)\" class=\"btn btn-success text-light\"><i class=\"far fa-arrow-alt-circle-up\" style=\"font-size:24px\"></i></a>
                      ";
                    }
                  //Impresion de los datos principales
                    echo "
                      <tr>
                        <td>$clave</td>
                        <td>$nombre_usuario</td>
                        <td>
                          <a onclick=\"editar_permisos_menu($dato)\" class=\"btn btn-primary text-light\"><i class=\"fas fa-edit\" style=\"font-size:24px\"></i></a>
                          $boton_nivel
                        </td>
                      </tr>
                    ";
                  //
                }
              //
            }
          //
        ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer"><div id="edicion"></div></div>
</div>
<script type="text/javascript">
  //funcion para ascender al usuario
  function ascender(opcion){
    var url="model/menu/update/ascender.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{
        clave:opcion
      },
      success: function(datos){$('#edicion').html(datos);}
    });
  }
  //funcion para degradar al usuario
  function degradar(opcion){
    var url="model/menu/update/degradar.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{
        clave:opcion
      },
      success: function(datos){$('#edicion').html(datos);}
    });
  }
  //Funcion para la tabla_colaborador
    $(document).ready( function () {
      var table = $('#tabla_colaborador').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
            { 
              extend: 'excelHtml5',
              text:'DESCARGAR EXCEL',
              customizeData: function(data) {
                for(var i = 0; i < data.body.length; i++) {
                  for(var j = 0; j < data.body[i].length; j++) {
                    data.body[i][j] = '\u200C' + data.body[i][j];
                  }
                }
              },
              orientation: 'landscape'
            },
            { extend: 'print', text:'IMPRIMIR' },{ extend: 'copy', text:'COPIAR' },
          ],
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