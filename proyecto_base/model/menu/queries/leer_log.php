<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Obtengo el archivo obtenido
  $archivo=campo_limpiado($_POST['log'],2,0,0);
?>
<div class="card">
  <div class="card-header">
    <a onclick="limpiar();" class="btn btn-sm btn-danger small btn-block text-light"><strong>CERRAR VISTA</strong></a>
    <h5><?php echo $archivo; ?></h5>
  </div>
  <div class="card-body">
     <table class="table table-bordered table-sm table-hover" id="tabla2">
      <thead>
        <tr>
          <th>ID</th>
          <th>USUARIO</th>
          <th>ERROR</th>
          <th>SENTENCIA</th>
          <th>ARCHIVO</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $ruta_archivo = A_LOGS . $archivo;
          // Abre el archivo en modo lectura
          $archivo = fopen($ruta_archivo, 'r');
          if ($archivo) {
            while (!feof($archivo)) {
              $linea = fgets($archivo); // Lee una línea del archivo
              // Ignorar líneas vacías
              if (trim($linea) !== '') {
                $contenido = campo_limpiado($linea, 2, 0, 1);
                $datos = explode("!!", $contenido);
                echo "
                  <tr>
                    <td>$datos[0]</td>
                    <td>$datos[1]</td>
                    <td>$datos[2]</td>
                    <td>$datos[3]</td>
                    <td>$datos[4]</td>
                  </tr>
                ";
              }
            }
            fclose($archivo); // Cierra el archivo
          } else {
            echo "<tr><td colspan='5'>No se pudo abrir el archivo</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla2').DataTable( {
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