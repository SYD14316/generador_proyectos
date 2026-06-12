<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<script>
    function tabla_menus_disponibles(){
      var clave=$("#usr").val();
      var url="model/menu/queries/menus_disponibles.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{clave:clave},
        beforeSend: function(){
          $("#menus_disponibles").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#menus_disponibles').html(datos);
        }
      });
    }
    function tabla_menus_asignados(){
      var clave=$("#usr").val();
      var url="model/menu/queries/menus_asignados.php"
      $.ajax({
        type: "POST",
        url:url,
        data:{clave:clave},
        beforeSend: function(){
          $("#menus_asignados").html("<div class='spinner-border'></div>");
        },
        success: function(datos){
          $('#menus_asignados').html(datos);
        }
      });
    }
    $(document).ready(tabla_menus_asignados());
    $(document).ready(tabla_menus_disponibles());
</script>
<div class="card">
  <div class="card-header"><h5>PERMISOS PARA EL USUARIO CLAVE <?php echo campo_limpiado($_POST['clave'],2,0,1); ?></h5></div>
  <div class="card-body">
    <div class='form-group' hidden>
      <input type='text' name='usr' id='usr' value="<?php echo $_POST['clave'] ?>" class='form-control' required=''>
    </div>
    <div id="respuesta"></div>
    <div class="row">
      <div class="col-md-6">
        <div id="menus_disponibles"></div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div id="menus_asignados"></div>
        </div>
      </div>
    </div>
  </div>
</div>