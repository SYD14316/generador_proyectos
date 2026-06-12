<script type="text/javascript">
  function registrar_menu(opcion){
    var url="model/menu/forms/registrar_menu.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ id:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }

  function asignar_menu(opcion){
    var url="model/menu/queries/usuarios.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ opcion:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }

  function menus(opcion){
    var url="model/menu/queries/menu.php"
    $.ajax({
      type: "POST",
      url:url,
      data:{ opcion:opcion },
      beforeSend: function(){
        $("#muestra").html("<div class='spinner-border'></div>");
        $('#expediente').html("");
      },
      success: function(datos){ $('#muestra').html(datos); }
    });
  }
  $(document).ready(registrar_menu());
</script>
<!--menus>>menus-->
<div class="card text-center">
  <div class="card-header">
    <a class="btn btn-sm text-sm btn-info" onclick="registrar_menu();">CREAR UN NUEVO MENÚ</a>
    <a class="btn btn-sm text-sm btn-info" onclick="menus();">MENÚS REGISTRADOS</a>
    <a class="btn btn-sm text-sm btn-info" onclick="asignar_menu('1');">ASIGNAR MENÚS A USUARIO</a>
    <a class="btn btn-sm text-sm btn-info" onclick="asignar_menu('2');">ASIGNAR PAGINA DE INICIO</a>
  </div>
  <div class="card-body">
    <div id="muestra"></div>
  </div>
</div>