<?php 
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Se obtienen los datos de los campos y se les da formato
  $inicio=campo_limpiado($_POST['inicio'],0,0,1);
  $fin=campo_limpiado($_POST['fin'],0,0,1);
?>
<div class="card">
  <div class="card-header"><h5>EVENTOS REGISTRADOS</h5></div>
  <div class="card-body">
    <table class="table table-bordered table-sm table-hover" id="tabla">
      <thead>
        <tr>
          <th>FECHA - HORA</th>
          <th>USUARIO</th>
          <th>EVENTO</th>
        </tr>
      </thead>
      <tbody>
        <?php
          //Defino la sentencia a ejecutar
            $sentencia="
              SELECT * FROM bitacora where fecha between '$inicio' and '$fin';
            ";
          //Ejecuto la sentencia y almaceno lo obtenido en una variable
            $resultado_sentencia=retorna_datos_sistema($sentencia);
          //Identifico si el reultado no es vacio
            if ($resultado_sentencia['rowCount'] > 0) {
              //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
              // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla_bitacora) {
                  //Extraigo los datos a variables
                    $fecha=campo_limpiado($tabla_bitacora['fecha'],0,1,0);
                    $hora=campo_limpiado($tabla_bitacora['hora'],0,1,0);
                    $accion=campo_limpiado($tabla_bitacora['accion'],0,1,0);
                  //Busco al usuario
                    $dato_colaborador=retorna_usuario($tabla_bitacora['usuario']);
                    $clave=$dato_colaborador['clave'];
                    $nombre=$dato_colaborador['nombre'];
                    $apellido=$dato_colaborador['apellido'];
                  //Impresion de los datos principales
                    echo "
                      <tr>
                        <td>$fecha - $hora</td>
                        <td>$clave - $nombre $apellido</td>
                        <td>$accion</td>
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
  //Funcion para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Mostrar todo' ]
        ],
        buttons:{
          buttons:[
            { extend: 'pageLength', text:'CANTIDAD' },
            { 
              extend: 'excelHtml5',
              text:'DESCARGAR EXCEL',
              filename: 'INVENTARIO DE IT - BITACORA DE EVENTOS <?php ECHO "(DEL $inicio AL $fin)" ?>',
              orientation: 'landscape'
            },
            { extend: 'print', text:'IMPRIMIR' },
            { extend: 'copy', text:'COPIAR' },
            { extend: 'colvis', text:'COLUMNAS' },
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