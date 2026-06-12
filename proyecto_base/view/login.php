  <?php
    //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
    //Se manda a llamar el archivo de configuración
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
    //Extraigo el tipo de protocolo desde donde se consulta
    $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    //Defino localizaciones para el logueo y deslogueo
    $_SESSION['location']=$protocol . '://' . $_SERVER['HTTP_HOST']. '/'.$_SESSION['ubi'].'/model/login.php';
    $_SESSION['logout']=$protocol . '://' . $_SERVER['HTTP_HOST']. '/'.$_SESSION['ubi'].'/model/logout.php';
    // Defino las redirecciones remotas
    $uri_g_bclb = 'https://benito.marista.mx/g-bclb.php?location='.campo_limpiado_nuevo($_SESSION['location'],1,0,1).'&logout='.campo_limpiado_nuevo($_SESSION['logout'],1,0,1);
    $uri_g_umg = 'https://benito.marista.mx/g-umg.php?location='.campo_limpiado_nuevo($_SESSION['location'],1,0,1).'&logout='.campo_limpiado_nuevo($_SESSION['logout'],1,0,1);
    $uri_m_bclb = 'https://benito.marista.mx/m-bclb.php?location='.campo_limpiado_nuevo($_SESSION['location'],1,0,1).'&logout='.campo_limpiado_nuevo($_SESSION['logout'],1,0,1);
    $uri_m_umg = 'https://benito.marista.mx/m-umg.php?location='.campo_limpiado_nuevo($_SESSION['location'],1,0,1).'&logout='.campo_limpiado_nuevo($_SESSION['logout'],1,0,1);
    //Si existe un mensaje de erro, lo imprimo
    if (isset($_SESSION['error'])) { echo $_SESSION['error']; }
  ?>
  <div class="text-center">
    <div class="jumbotron text-center" id="page-body">
      <img src="img/logo-esm.png" class="img-fluid"><br>
    </div>
    <div class="container-fluid" role="main">
      <div class="row ">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <div id="respuesta_iniciar_sesion"></div>
          <div class="row">
            <div class="col-md-12">
              <h5 class="h5 mb-3 fw-normal"><strong>INGRESA CON TU CUENTA INSTITUCIONAL DE GOOGLE O MICROSOFT</strong></h5>
              <label></label>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <a class='btn btn-danger btn-block' href="<?php echo $uri_g_bclb ?>"><strong><i class='fab fa-google'></i> Google CLB</strong></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <a class='btn btn-danger btn-block' href="<?php echo $uri_g_umg ?>"><strong><i class='fab fa-google'></i> Google UMG</strong></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <a class='btn btn-primary btn-block'  href="<?php echo $uri_m_bclb ?>" ><strong><i class='fab fa-windows'></i> Microsoft CLB</strong></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <a class='btn btn-primary btn-block'  href="<?php echo $uri_m_umg ?>" ><strong><i class='fab fa-windows'></i> Microsoft UMG</strong></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </div>
</body>
</html>