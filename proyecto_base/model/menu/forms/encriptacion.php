<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
  ?>
<script type="text/javascript">
  function encriptar(){
    var cadena=$("#cadena").val();
    var url="model/menu/queries/encriptar.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ cadena:cadena },
      beforeSend: function(){
        $("#respuesta_sql").html("<div class='spinner-border'></div>");
        $("#respuesta_sql").html("");
      },
      success: function(datos){ $("#respuesta_sql").html(datos); }
    });
  }
  function desencriptar(){
    var cadena=$("#cadena").val();
    var url="model/menu/queries/desencriptar.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ cadena:cadena },
      beforeSend: function(){
        $("#respuesta_sql").html("<div class='spinner-border'></div>");
        $("#respuesta_sql").html("");
      },
      success: function(datos){ $("#respuesta_sql").html(datos); }
    });
  }
</script>
  <div class="card text-center">
    <div class="card-body">
      <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_sql" id="frm_sql">
        <div class="form-group">
          <textarea name="cadena" id="cadena" class="form-control"></textarea>
        </div>
        <div class="form-actions">
        </div>
      </form>
      <div class="row">
        <div class="col-md-6">
          <a class="btn btn-block text-sm btn-success" onclick="encriptar();">ENCRIPTAR</a>
        </div>
        <div class="col-md-6">
          <a class="btn btn-block text-sm btn-primary" onclick="desencriptar();">DESENCRIPTAR</a>
        </div>
      </div>
    </div>
    <div class="card-footer" id="respuesta_sql"></div>
  </div>