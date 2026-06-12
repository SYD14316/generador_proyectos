<div class="card">
  <div class="card-header"><h5>REGISTRAR UN NUEVO MENÚ</h5></div>
  <div class="card-body">
    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frm_crear_menu" id="frm_crear_menu">
      <div class="row">
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>NOMBRE DEL GRUPO</strong></span>
            </div>
            <input type='text' name='nombre_grupo' class='form-control' required=''/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>NOMBRE DEL MENÚ</strong></span>
            </div>
            <input type='text' name='nombre_menu' class='form-control' required=''/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DIRECTORIO DEL MENÚ</strong></span>
            </div>
            <input type='text' name='directorio' class='form-control' required=''/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>SUB-DIRECTORIO DEL MENÚ</strong></span>
            </div>
            <input type='text' name='sub_directorio' class='form-control' placeholder="EJEMPLO: sub-dir1/sub-dir 2/... (DEJAR EN BLANCO SI NO APLICA)"/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>ARCHIVO DEL MENÚ</strong></span>
            </div>
            <input type='text' name='archivo' class='form-control' placeholder="Nombre del archivo .php al que se referenciara" required=""/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3 input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><strong>DESCRIPCION DEL MENÚ</strong></span>
            </div>
            <input type='text' name='descripcion' class='form-control' placeholder="Pequeña descripcion de lo que hac el MENÚ"/>
          </div>
        </div>
      </div>
      <div class="form-group">
        <input type="submit" value="REGISTRAR" class="btn btn-success btn-block" onclick="crear_menu();"/>
      </div>
      <div class="form-group">
        <div id="respuesta_crear_menu"></div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(function crear_menu(){
    $("#frm_crear_menu").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("frm_crear_menu"));
      formData.append("dato", "valor");
      $.ajax({
        url: "model/menu/insertion/crear_menu.php",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){ $("#respuesta_crear_menu").html("<div class='spinner-border'></div>"); },
      })
      .done(function(res){ $("#respuesta_crear_menu").html(res); });
    });
  });
</script>