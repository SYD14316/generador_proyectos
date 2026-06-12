<?php
  define('TARGET', 'proyecto_base');
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  $_SESSION['ubi']=TARGET;
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.TARGET.'/lib/config.php';
  include_once 'view/header.php';
  define( 'VMIN_OPERA', '62' );
  define( 'VMIN_EXPLORER', '9999' );
  define( 'VMIN_EDGE', '70' );
  define( 'VMIN_CHROME', '75' );
  define( 'VMIN_FIREFOX', '70' );
  define( 'VMIN_SAFARI', '604.2' );
  if (!isset($_POST['datax'])) { 
    echo "
      <script type='text/javascript'>
        function submitform(){ document.getElementById('formReload').submit(); };
      </script>
      <form id='formReload' method='POST' hidden='True'>
        <input type='text' name='datax' id='datax'>
      </form>
    ";
    ?>
    <script type="text/javascript">
      var objappVersion = navigator.appVersion;
      var browserAgent = navigator.userAgent;
      var browserName = navigator.appName;
      var browserVersion = '' + parseFloat(navigator.appVersion);
      var browserMajorVersion = parseInt(navigator.appVersion, 10);
      var Offset, OffsetVersion, ix;
      var versionMinimaOpera = <?php echo VMIN_OPERA; ?>;
      var versionMinimaExplorer = <?php echo VMIN_EXPLORER; ?>;
      var versionMinimaEdge = <?php echo VMIN_EDGE; ?>;
      var versionMinimaChrome = <?php echo VMIN_CHROME; ?>;
      var versionMinimaFirefox = <?php echo VMIN_FIREFOX; ?>;
      var versionMinimaSafari = <?php echo VMIN_SAFARI; ?>;
      
      if ((OffsetVersion = browserAgent.indexOf("OPR")) != -1) {
        // For Opera
        browserName = "Opera";
        browserVersion = browserAgent.substring(OffsetVersion + 4);
      } else if ((OffsetVersion = browserAgent.indexOf("Edg")) != -1) {
        // For Microsoft Edge
        browserName = "Edge";
        browserVersion = browserAgent.substring(OffsetVersion + 4); 
      } else if ((OffsetVersion = browserAgent.indexOf("MSIE")) != -1) {
        // For Microsoft Internet Explorer
        browserName = "Explorer";
        browserVersion = browserAgent.substring(OffsetVersion + 5);
      } else if ((OffsetVersion = browserAgent.indexOf("Firefox")) != -1) {
        // For Firefox
        browserName = "Firefox";
        browserVersion = browserAgent.substring(OffsetVersion + 8);
      } else if ((OffsetVersion = browserAgent.indexOf("Chrome")) != -1) {
        // For Chrome
        browserName = "Chrome";
        browserVersion = browserAgent.substring(OffsetVersion + 7);
      } else if ((OffsetVersion = browserAgent.indexOf("AppleWebKit")) != -1) {
        // For Safari
        browserName = "Safari";
        browserVersion = browserAgent.substring(OffsetVersion + 12);
        if ((OffsetVersion = browserAgent.indexOf("Version")) != -1)
          browserVersion = browserAgent.substring(OffsetVersion + 8);
      }
      // Trimming the fullVersion string at semicolon/space if present
      if ((ix = browserVersion.indexOf(";")) != -1)
        browserVersion = browserVersion.substring(0, ix);
      if ((ix = browserVersion.indexOf(" ")) != -1)
          browserVersion = browserVersion.substring(0, ix);
        browserMajorVersion = parseInt('' + browserVersion, 10);
      if (isNaN(browserMajorVersion)) {
        browserVersion = '' + parseFloat(navigator.appVersion);
        browserMajorVersion = parseInt(navigator.appVersion, 10);
      }

      var browserSubVersion = (browserMajorVersion + '.' + parseInt(browserVersion.substring((('' + browserMajorVersion).length) + 1), 10)) * 1;

      if ((("Opera" == browserName) && (versionMinimaOpera > browserSubVersion)) || (("Explorer" == browserName) && (versionMinimaExplorer > browserSubVersion)) || (("Edge" == browserName) && (versionMinimaEdge > browserSubVersion)) || (("Firefox" == browserName) && (versionMinimaFirefox > browserSubVersion)) || (("Chrome" == browserName) && (versionMinimaChrome > browserSubVersion)) || (("Safari" == browserName) && (versionMinimaSafari > browserSubVersion))) {

        <?php
          $hash=password_hash('¡El navegador no soporta la página!', PASSWORD_DEFAULT);
          echo "$('#datax').val('$hash');";
        ?>
        submitform();
      } else {

        <?php
          $hash=password_hash('¡El navegador sí soporta la página!', PASSWORD_DEFAULT);
          echo "$('#datax').val('$hash');";
        ?>
        submitform();
      }
    </script>
    <?php
    die();
  } else {
    if (password_verify('¡El navegador sí soporta la página!', $_POST['datax'])) {
      if(isset($_SESSION[TARGET]['id'])){
        if($_SESSION[TARGET]['id']!=null){
          $usuario=$_SESSION[TARGET]['id'];
          include_once 'view/body.php';
        }else{
          //echo "<script>window.location.href = 'https://intranet.marista.mx';</script>";
          echo "<script>window.location.href = 'http://localhost';</script>";
        }
      }else{
        //echo "<script>window.location.href = 'https://intranet.marista.mx';</script>";
        echo "<script>window.location.href = 'http://localhost';</script>";
      }
      die();
    } else {
      session_destroy();
      include_once 'model/no_permitido.php';
      die();
    }
  }