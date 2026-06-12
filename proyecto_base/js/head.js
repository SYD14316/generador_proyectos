//Funcion para el inicio de sesion
  function acceso(){
    $.ajax({
      type: "POST", 
      url: "model/login.php",
      data: $("#loginform").serialize(),
      beforeSend: function(){
        $("#respuesta_iniciar_sesion").html("<div class='spinner-border'></div>");
      },
      success: function(data){
        if ($.trim(data)== 'ok') {
          location.reload(true); 
        }else{
          $("#respuesta_iniciar_sesion").html(data);
        }
      },
    });
    return false;
  }
//Funcion para el inicio de sesion
  function acceso_google(){
    $.ajax({
      type: "POST", 
      url: "model/login_google.php",
      beforeSend: function(){$("#respuesta_iniciar_sesion").html("<div class='spinner-border'></div>");},
      success: function(data){
        if ($.trim(data)== 'ok') {
          location.reload(true); 
        }else{
          $("#respuesta_iniciar_sesion").html(data);
        }
      },
    });
    return false;
  }
//Funcion para el menu
  function menu(opcion){
      $.ajax({
        type: "POST", 
        url: "lib/controller.php",
        data: {opt_form: opcion},
        beforeSend: function(){
          $("#page-body").html("<div class='spinner-border text-center'></div>");
        },
        success: function(respuesta){
          $("#page-body").html(respuesta);
        }
      });
    return false;
  }