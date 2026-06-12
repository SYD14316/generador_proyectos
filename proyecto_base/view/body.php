<script>
  //tablas de talonarios
    function recarga_datos_recurrente(opcion){
      var url="view/recargar_datos_recurrente.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{id:opcion},
        beforeSend: function(){
          $("#r_datos").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#r_datos').html(datos);
        }
      })
    }
    $(document).ready(recarga_datos_recurrente());
    setInterval('recarga_datos_recurrente()',300000);
  //
</script>
<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Incluyo el menu a la pagina
    include_once A_VIEW.'menu.php';
  //Reviso el indicador de pantalla principal
    $pantalla=campo_limpiado($_SESSION[UBI]['pag_principal'],2);
    if ($pantalla>0) {
      //Defino el numero del menu a llamar
      $numero="'".campo_limpiado($pantalla,1)."'";
      //mando a llamar el menu
      echo "
        <script>
          $(document).ready(menu($numero));
        </script>
      ";
    }
  //
?>

<div id="page-body">
  <img  class="mx-auto d-block" src="img/imagen_fondo.jpg" style="max-width:600px;">
</div>
<div id="r_datos"></div>
<?php
  //Mando llamar los plug-in de datatables y el pie de pagina
  include_once  A_MODEL.'datatables.php';
  include_once  A_VIEW.'footer.php';
?>
<script>
  function limpiar(){
    $("#expediente").html("");
  }
</script>