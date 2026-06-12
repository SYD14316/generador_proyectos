<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
?>
<div class="card text-center">
  <div class="card-header"><h5>BITÁCORA POR FECHA</h5></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_buscar_bitacora" id="frm_buscar_bitacora">
      <div class="row">
        <div class="col-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE INICIO DEL PERIODO</strong></span>
            </div>
            <input type="date" name="inicio" id="inicio" class="form-control" value="<?php echo ahora(1) ?>" required="">
          </div>
        </div>
        <div class="col-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>FECHA DE FIN DEL PERIODO</strong></span>
            </div>
            <input type="date" name="fin" id="fin" class="form-control" value="<?php echo ahora(1) ?>" required="">
          </div>
        </div>
      </div>
      <div class="form-group">
        <input type="submit" value="BUSCAR" class="btn btn-success btn-block" onclick="buscar_bitacora();">
      </div>
      <div class="form-group">
        <div id="respuesta_buscar_bitacora"></div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(function buscar_bitacora(){
    $("#frm_buscar_bitacora").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_buscar_bitacora"));
      formData.append("dato", "valor");
      $.ajax({
        url: "model/menu/queries/bitacora.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){ $("#respuesta_buscar_bitacora").html("<div class='spinner-border'></div>"); },
      })
      .done(function(res){ $("#respuesta_buscar_bitacora").html(res); });
    });
  });
</script>