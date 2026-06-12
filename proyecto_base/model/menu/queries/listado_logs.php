<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script type="text/javascript">
  function leer(opcion){
    var url="model/menu/queries/leer_log.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ log:opcion },
      beforeSend: function(){
        $("#expediente").html("<div class='spinner-border'></div>");
      },
      success: function(datos){ $('#expediente').html(datos); }
    });
  }
  function eliminar(opcion){
    var url="model/menu/update/eliminar_log.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ log:opcion },
      beforeSend: function(){
        $("#expediente").html("<div class='spinner-border'></div>");
      },
      success: function(datos){ $('#expediente').html(datos); }
    });
  }
</script>
<div class="card text-center">
  <div class="card-header"><h5>LECTOR DE LOG'S DE ERRORES</h5></div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-header"><h5>ARCHIVOS</h5></div>
          <div class="card-body">
            <table class="table table-bordered table-sm table-hover" id="tabla">
              <thead>
                <tr>
                  <th>ARCHIVO</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $contador=0;
                  $thefolder = A_RAIZ."logs";
                  if ($handler = opendir($thefolder)) {
                    while (false !== ($file = readdir($handler))) {
                      $dato="'".campo_limpiado($file,1,0,1)."'";
                      if ($contador>1) {
                        echo "
                        <tr>
                          <td>$file</td>
                          <td>
                            <a onclick=\"leer($dato)\" class=\"btn btn-sm btn-primary text-light\"><i class='fas fa-eye' style='font-size:24px'></i></a>
                            <a onclick=\"eliminar($dato)\" class=\"btn btn-sm btn-danger text-light\"><i class='fas fa-trash' style='font-size:24px'></i></a>
                          </td>
                        </tr>
                      ";
                      }
                      $contador++;
                    }
                    closedir($handler);
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div id="expediente"></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  //Funcio para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
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
        searching: false, // Esta opción deshabilita la barra de búsqueda
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