<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //
  ?>
  <div class="card text-center">
    <div class="card-body">
      <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_sql" id="frm_sql">
        <div class="form-group">
          <textarea name="codex" class="form-control" placeholder="Codigo SQL a ejecutar" required=""></textarea>
        </div>
        <div class="form-actions">
          <input type="submit" value="EJECUTAR" class="btn btn-sm btn-success" onclick="ejecutar();">
        </div>
      </form>
    </div>
    <div class="card-footer" id="respuesta_sql"></div>
  </div>
  <script>
    $(function ejecutar(){
      $("#frm_sql").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("frm_sql"));
        formData.append("dato", "valor");
        $.ajax({
          url: "model/menu/queries/ejecucion.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){
            $("#respuesta_sql").html("<div class='spinner-border'></div>");
          },
        })
        .done(function(res){
          $("#respuesta_sql").html(res);
        });
      });
    });
  </script>