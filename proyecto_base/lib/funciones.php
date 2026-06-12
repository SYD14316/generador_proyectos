<?php
  //Se revisa si la sesión esta iniciada y sino se inicia
    if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Defino la constante de la base de datos del sistema en base a la variable de sesion
    define('DB_SYSTEM',$_SESSION['ubi']);
  //Defino la zona horaria
    date_default_timezone_set('America/Monterrey');
  //defino el charset
    header('Content-Type: text/html; charset=utf-8');
  //Llamada de librerías de PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
  //Tipos de extensiones de archivos permitidos
    $extensionesPermitidas = array('jpg', 'jpeg', 'pdf');
  //Funcion para eliminar tildes y la virgulilla de la Ñ
    /**
      * Elimina tildes y la virgulilla de la Ñ de una cadena.
      *
      * @param string $cadena Texto de entrada.
      * @return string Texto sin acentos y con 'Ñ' reemplazada por 'N'.
      * @example eliminar_tildes('Ñandú ÁÉÍÓÚ'); // 'Nandu AEIOU'
    */
    function eliminar_tildes($cadena){
      $cadena = str_replace('Á','A',$cadena);
      $cadena = str_replace('É','E',$cadena);
      $cadena = str_replace('Í','I',$cadena);
      $cadena = str_replace('Ó','O',$cadena);
      $cadena = str_replace('Ú','U',$cadena);
      $cadena = str_replace('Ñ','N',$cadena);
      return $cadena;
    }
  //Funcion para reemplazar ampersand y doble codificacion
    /**
      * Normaliza entidades HTML relacionadas con &amp; y doble codificación.
      * Convierte secuencias como '&amp;amp;' en '&'.
      *
      * @param string $texto Texto que puede contener entidades HTML.
      * @return string Texto decodificado.
      * @example reemplaza_ampersand('Tom &amp;amp; Jerry'); // 'Tom & Jerry'
    */
    function reemplaza_ampersand($texto){
      // Primero intentamos decodificar entidades HTML repetidamente (maneja &amp;amp; -> &amp; -> &)
      $prev = null;
      $decoded = $texto;
      $attempts = 0;
      while ($decoded !== $prev && $attempts < 5) {
        $prev = $decoded;
        // Usamos ENT_QUOTES|ENT_HTML5 para manejar la mayoría de entidades
        $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $attempts++;
      }
      // Como respaldo, eliminamos cualquier cadena residual "amp;" que pudiera quedar (p. ej. entradas sin el & delante)
      $decoded = str_replace(array('amp;amp;','amp;'), '', $decoded);
      return $decoded;
    }
  //Funcion para poner y quitar comillas
    /**
      * Reemplaza el marcador 'çÇç' por comillas dobles.
      *
      * @param string $texto Texto de entrada.
      * @return string Texto con comillas dobles reales.
    */
    function pone_comillas($texto){ return str_replace('çÇç', '"', $texto); }
    /**
      * Reemplaza comillas dobles por el marcador 'çÇç'.
      *
      * @param string $texto Texto de entrada.
      * @return string Texto con marcador en vez de comillas dobles.
    */
    function quita_comillas($texto){ return str_replace('"', 'çÇç', $texto); }
  //Funcion para eliminar caracteres especiales
    /**
      * Elimina un conjunto de caracteres especiales de una cadena.
      *
      * @param string $cadena Texto a limpiar.
      * @return string Texto sin los caracteres especiales definidos.
    */
    function elimina_especiales($cadena){
      //Defino un arreglo con los caracteres especiales a eliminar
      $especiales= array(
        '|',
        '°',
        '¬',
        '!',
        '"',
        '#',
        '$',
        '%',
        '&',
        "/",
        '(',
        ')',
        '=',
        '?',
        '¡',
        '¿',
        ',',
        ".",
        ";",
        "*",
        '<',
        '>',
        "\n"
      );
      //Elimino los caracteres listados del texto
      $resultado=str_replace($especiales,"",$cadena);
      //Devuelvo el resultado corregido
      return $resultado;
    }
  //Funcion para transformar horas y fechas
    /**
      * Formatea una hora.
      *
      * @param string $hora Hora en formato 'HH:MM:SS'.
      * @param string $formato '12' para 12h, '24' para 24h (por defecto).
      * @param string $separador Separador a usar en formato 24h (por defecto ':').
      * @return string Hora formateada.
      * @example transforma_hora('14:05:10','12'); // '2:05 pm'
    */
    function transforma_hora($hora,$formato="24",$separador=":"){
      //Identifico si se definio un formato de 12 o 24 horas
        if ($formato=="12") {
          //Transformo la hora a un formato de 12 horas
            $texto=date('g:i a', strtotime($hora));
          //
        }else{
          //Obtengo y separo la hora en horas, minutos y segundos
            $dato=explode(":", $hora);
          //Almaceno las partes obtenidas
            $horas=$dato[0];
            $minutos=$dato[1];
            $segundos=$dato[2];
          //Concateno los datos con el separador definido
            $texto=$horas.$separador.$minutos.$separador.$segundos;
          //
        }
      //Retorno el texto formateado
        return $texto;
      //
    }
  //Funcion para transformar fecha solamente
    /**
      * Convierte una fecha 'YYYY-MM-DD' al formato solicitado.
      *
      * @param string $fecha Fecha base.
      * @param int $tipo 0: mes numérico, 1: mes en texto.
      * @param string $separador Separador entre día, mes y año.
      * @return string Fecha formateada.
      * @example transforma_fecha('2025-11-25',1,'_'); // '25_Noviembre_2025'
    */
    function transforma_fecha($fecha,$tipo=0,$separador="-"){
      //Obtenemos la fecha y la separamos por su guion medio
        $dato = explode("-",$fecha);
      //Almaceno cada parte en una variable
        $ano=$dato[0];
        $mes=$dato[1];
        $dia=$dato[2];
      //Evaluamos si se sequiere que se convierta a dexto el mes
        if ($tipo==1) {
          //Obtengo el mes y lo paso a texto
            switch ($dato[1]) {
              case '1':
                $mes='Enero';
              break;
              case '2':
                $mes='Febrero';
              break;
              case '3':
                $mes='Marzo';
              break;
              case '4':
                $mes='Abril';
              break;
              case '5':
                $mes='Mayo';
              break;
              case '6':
                $mes='Junio';
              break;
              case '7':
                $mes='Julio';
              break;
              case '8':
                $mes='Agosto';
              break;
              case '9':
                $mes='Septiembre';
              break;
              case '10':
                $mes='Octubre';
              break;
              case '11':
                $mes='Noviembre';
              break;
              case '12':
                $mes='Diciembre';
              break;
            }
          //
        }
      //Concateno los datos con el separador definido
        $texto=$dia.$separador.$mes.$separador.$ano;
      //Retorno el dato obtenido
        return $texto;
      //
    }
  //Devuelve la fecha y hora actuales
    /**
      * Obtiene la fecha y/o hora actual según tipo.
      *
      * @param int|string $tipo 1: fecha (Y-m-d), 2: hora (H:i:s), 3: timestamp (Y-m-d H:i:s)
      * @return string Valor formateado correspondiente.
      * @example $fecha = ahora(1); // 2025-11-25
      * @example $hora  = ahora(2); // 14:32:10
      * @example $ts    = ahora(3); // 2025-11-25 14:32:10
   */
    function ahora($tipo){
      //Se obtiene el timepo actual
        $hoy = getdate();
      //Se evalua el tipo de dato que se desea obtener
        switch ($tipo) {
          //Se solicita la fecha
            case '1':
              $actual=date('Y-m-d');
            break;
          //Se solicita la hora
            case '2':
              $actual=date('H:i:s');
            break;
          //Se solicitan el timestamp actual
            case '3':
              $actual=date('Y-m-d H:i:s');
            break;
          //
        }
      //Retorno el dato formateado
        return $actual;
      //
    }
  //Genera una referecia de fecha
    /**
      * Genera una referencia de fecha con mes en texto y guiones bajos.
      *
      * @return string Cadena como '25_Noviembre_2025_'
      * @example $ref = ref_fecha(); // 25_Noviembre_2025_
   */
    function ref_fecha(){
      //Obtengo la fecha actual en texto separada por guin bajo
        $texto=transforma_fecha(ahora(1),1,"_")."_";
      //Devuelvo el dato formateado
        return $texto;
      //
    }
  //Devuelve la referencia horaria
    /**
      * Devuelve referencia horaria compacta sin separadores.
      *
      * @return string Hora en formato HHMMSS.
      * @example $rh = referencia_horaria(); // 143210
    */
    function referencia_horaria(){
      //Obtengo la referencia horaria con base en la funcion de transforma hora
        $texto=transforma_hora(ahora(2),"24","");
      //Retorno el texto formateado
        return $texto;
      //
    }
  //Devuelve la referencia temporal
    /**
      * Genera una referencia temporal combinando fecha y hora sin separadores.
      *
      * @return string Cadena como '20251125-143210'.
      * @example $rt = referencia_temporal(); // 20251125-143210
    */
    function referencia_temporal(){
      //Obtengo la hora actual
      $time=ahora(2);
      //Le elimino los : y la guardo en la variable
      $tiempo=str_replace(':','',$time);
      //Obtengo la fecha actual
      $date=ahora(1);
      //Le elimino los - y la guardo en la variable
      $dia=str_replace('-','',$date);
      //Realizo cun concatenado
      $referencia_temporal=$dia."-".$tiempo;
      //Devuelvo el valor
      return $referencia_temporal;
    }
  //Devuelve el dato limpiado, le da formato al texto y lo encripta según los identificadores
    /**
      * Limpia, formatea y opcionalmente encripta/desencripta un dato
      * 
      * Aplica limpieza HTML, conversión de mayúsculas/minúsculas y encriptación según los parámetros.
      * Detiene la ejecución con alert si el campo está vacío y $llenado=1.
      * 
      * @param string $dato Dato a procesar (texto plano o encriptado)
      * @param int $encript Tipo de encriptación:
      *                     - 0: sin encriptación
      *                     - 1: encriptar
      *                     - 2: desencriptar
      * @param int $mayus Formato de texto:
      *                   - 0: sin cambios
      *                   - 1: MAYÚSCULAS
      *                   - 2: minúsculas
      * @param int $llenado Validación de campo vacío:
      *                     - 0: no validar
      *                     - 1: detener si está vacío (muestra alert y die())
      * @param string $campo Nombre del campo (opcional, para mensajes de alerta)
      * @return string Dato limpio, formateado y/o encriptado/desencriptado
      * 
      * @example
      * // Limpiar y convertir a mayúsculas (sin encriptar, validar llenado)
      * $nombre = campo_limpiado($_POST['nombre'], 0, 1, 1);
      * 
      * @example
      * // Encriptar en minúsculas
      * $email = campo_limpiado($_POST['email'], 1, 2, 0);
      * 
      * @example
      * // Desencriptar y convertir a mayúsculas
      * $clave = campo_limpiado($dato_encriptado, 2, 1, 0);
   */
    function campo_limpiado($dato, $encript = 0, $mayus = 0, $llenado = 0, $campo = "") {
      if (($mayus==1)&&($encript==1)) { //Mayusculas y encriptar
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Conversion a mayúsculas
          $dato = mb_strtoupper($dato);
        //Encriptacion
          $resultado = encriptar_ligero($dato);
        //
      }elseif (($mayus==2)&&($encript==1)) { //Minusculas y encriptar
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Conversion a minusculas
          $dato = strtolower($dato);
        //Encriptacion
          $resultado = encriptar_ligero($dato);
        //
      }elseif (($mayus==0)&&($encript==1)) { //Solo encriptar
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Encriptacion
          $resultado = encriptar_ligero($dato);
        //
      }elseif(($mayus==1)&&($encript==2)) { //Mayusculas y desencriptar
        //Revision de cadena llena
          if (empty($dato)) {
            if ($campo!="") {
              //Se imprime un mensaje de alerta
                echo "<script>alert('VALOR O DATO INVALIDO: $campo');</script>";
              //
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('VALOR O DATO INVALIDO');</script>";
              //
            }
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $dato = desencriptar_ligero($dato);
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Conversion a mayúsculas
          $dato = mb_strtoupper($dato);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }elseif (($mayus==2)&&($encript==2)) { //Minusculas y desencriptar
        //Revision de cadena llena
          if (empty($dato)) {
            if ($campo!="") {
              //Se imprime un mensaje de alerta
                echo "<script>alert('VALOR O DATO INVALIDO: $campo');</script>";
              //
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('VALOR O DATO INVALIDO');</script>";
              //
            }
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $campo = desencriptar_ligero($dato);
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Conversion a minusculas
          $dato = strtolower($dato);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }elseif (($mayus==0)&&($encript==2)) { //Solo desencriptar
        //Revision de cadena llena
          if (empty($dato)) {
            if ($campo!="") {
              //Se imprime un mensaje de alerta
                echo "<script>alert('VALOR O DATO INVALIDO: $campo');</script>";
              //
            }else{
              //Se imprime un mensaje de alerta
              echo "<script>alert('VALOR O DATO INVALIDO');</script>";
              //
            }
            //Se deiene el procedimiento
            die();
          }
        //Desencriptacion
          $dato = desencriptar_ligero($dato);
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }elseif(($mayus==1)&&($encript==0)) { //Mayusculas
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Conversion a mayúsculas
          $dato = mb_strtoupper($dato);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }elseif (($mayus==2)&&($encript==0)) { //Minusculas
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Conversion a minusculas
          $dato = strtolower($dato);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }elseif (($mayus==0)&&($encript==0)) { //Ningun formato
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Limpieza del campo
          $dato = htmlspecialchars(limpiar_campo($dato),ENT_QUOTES);
        //Revision de llenado
          if ($llenado==1) {
            if (empty($dato)) {
              //Verifico si elcampo es diferente de vacio
                if ($campo!="") {
                  //Se imprime un mensaje de alerta
                    echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO: $campo');</script>";
                  //
                }else{
                  //Se imprime un mensaje de alerta
                  echo "<script>alert('¡ATENCION!, UNO DE LOS DATOS ESTA INCOMPLETO');</script>";
                  //
                }
              //Se deiene el procedimiento
                die();
              //
            }
          }
        //Envio el resultado
          $resultado = $dato;
        //
      }
      //Se regresa el dato ya formateado
      return $resultado;
    }
  //Función para enviar correo y dar una respuesta
    function manda_correo($cabecera,$destino,$mensaje,$respuesta, $adjunto = ""){
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->CharSet="utf-8";
        $mail->Host=HOST_SEND;
        $mail->SMTPAuth=true;
        $mail->Username=MAIL_SEND;
        $mail->Password=PASS_SEND ;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port=PORT_SEND;
        $mail->setFrom(MAIL_SEND);
        $mail->addAddress($destino);
        $mail->isHTML(true);
        $mail->Subject=$cabecera;
        $mail->Body=$mensaje;
        $mail->AltBody=$mensaje;
        // Adjuntar archivo si se proporciona
          if (!empty($adjunto) && file_exists($adjunto)) {
            $mail->addAttachment($adjunto);
          }
        //
        $mail->send();
        return $respuesta;
      } catch (Exception $e) {
        //Almaceno el error en una variabLe
          $error=$e->getMessage();
        //Ubico el archivo desde donde se presenta el error
          $archivo=__FILE__."::Funcion manda_correo";
        //Mando a escribir el mensaje
          escribir_log($error,$mail,$archivo);
        //Detengo el procedimiento
          die();
        //
      }
    }
  //Función para escribir el log de fallos
    /**
      * Escribe un registro de error en un archivo de log.
      *
      * @param string $error Mensaje de error a registrar.
      * @param string $sentencia Sentencia SQL o acción que causó el error.
      * @param string $archivo Ruta del archivo que invoca la función para depuración.
      * 
      * @example
      * escribir_log(
      *   "Error al conectar a la base de datos",
      *   "SELECT  * FROM usuarios",
      *   __FILE__
      * );
   */
    function escribir_log($error,$sentencia,$archivo) {
      //Le quito los saltos de linea a la sentencia
        $sentencia = str_replace("\n",'',$sentencia);
      //Creeo la referencia de tiempo
        $referencia=referencia_horaria();
      //Evaluo si existe un logueo
        if(isset($_SESSION[UBI]['id'])){
          //Obtengo el nombre del usuario
            $usuario=campo_limpiado($_SESSION[UBI]['nombre'],2,0,0)." ".campo_limpiado($_SESSION[UBI]['apellido'],2,0,0);
          //Creo la linea a escribir
            $linea="$referencia!!$usuario!!$error!!$sentencia!!$archivo";
          //
        }else{
          //Creo la linea a escribir
            $linea="$referencia!!NO IDENTIFICADO!!$error!!$sentencia!!$archivo";
          //
        }
      //Encripto el texto
        $texto=campo_limpiado($linea,1,0,0);
      // Ruta y nombre del archivo de log
        $nombre_txt = A_LOGS . ahora(1) . "-ErrorLog.txt";
      // Intentar abrir el archivo
        $archivo = fopen($nombre_txt, 'a+');
      // Verificar si el archivo se abrió correctamente
        if ($archivo) {
          // Escribir en el archivo
            fwrite($archivo, $texto . "\n");
          // Cerrar el archivo
            fclose($archivo);
          // Mostrar mensaje de error al usuario
            echo "<script>alert('¡ERROR!, CONTACTA CON EL ADMINISTRADOR (CODIGO: $referencia)');</script>";
          //
        } else {
          // Mostrar mensaje de error al usuario si no se pudo abrir el archivo
            echo "<script>alert('¡ERROR!');</script>";
          //
        }
      //
    }
  //Función para ejecutar sentencias dentro de la base de datos de la plataforma
    /**
      * Registra un evento en la tabla bitacora de la base de datos
      * 
      * @param array $campos Nombres de las columnas adicionales (sin fecha/hora),
      * Campos válidos: id_usuario
      * @param array $datos Valores correspondientes a los campos (mismo orden)
      * @param string $direccion (Opcional) Ruta del archivo que invoca la función para depuración
      * @return bool true si se ejecutó correctamente
      * 
      * @example
      * registra_bitacora(
      *   ['usuario','accion','detalle'],
      *   ['1','INICIO_SESION','Login exitoso'],
      *   __FILE__
      * );
   */
    function registra_bitacora($campos,$datos,$direccion = "") {
      //Verifico si se incluyo una direccion de error
        if (campo_limpiado($direccion,2)!="") {
          $agregado=", Referenciado desde $direccion";
        }else{
          $agregado=Null;
        }
      //Defino 2 variables vacias
        $texto_campos=Null;
        $texto_datos=Null;
      //Se obtiene datos iniciales
        $fecha=ahora(1);
        $hora=ahora(2);
        $usuario=campo_limpiado($_SESSION[UBI]['clave'],2,0,1,"USUARIO LOGUEADO");
      //Procesamiento de arreglo de campos
        //Obtengo la cantidad de elementos en el arreglo
          $cantidad_campos=count($campos);
        //Creeo un ciclo for para recorrer todo el arreglo
          for ($i=0; $i < $cantidad_campos ; $i++) { 
            //Concateno el campo y le agrego una coma y espacio al final
              $texto_campos.=$campos[$i].", ";
            //
          }
        //
      //Procesamiento de arreglo de datos
        //Obtengo la cantidad de elementos en el arreglo
          $cantidad_datos=count($datos);
        //Creeo un ciclo for para recorrer todo el arreglo
          for ($i=0; $i < $cantidad_datos ; $i++) { 
            //Concateno el campo y le agrego una coma y espacio al final
              $texto_datos.="'".$datos[$i]."', ";
            //
          }
        //
      //Se defina la sentencia a ejecutar
        $sentencia="
          INSERT INTO bitacora (
            fecha,
            hora,
            $texto_campos
            usuario
          ) VALUES (
            '$fecha',
            '$hora',
            $texto_datos
            '$usuario'
          );
        ";
      //Se ejecuta la sentencia
        ejecuta_sentencia_sistema($sentencia,true,"::Funcion registra_bitacora$agregado");
      //
    }
  //Función para obtener los datos de una sentencia ejecutada en la BD central
    /**
      * Retorna los datos obtenidos de una sentencia SQL ejecutada en la base de datos.
      *
      * @param string $sentencia La sentencia SQL a ejecutar.
      * @param string $plataforma La plataforma de base de datos a la que se conectará, si no se define una, se usará DB_SYSTEM.
      * @param string $direccion (Opcional) Ruta del archivo que invoca la función para depuración.
      * @return array Un arreglo asociativo que contiene los datos obtenidos y el número de filas afectadas.
      * 
      * @example
      * $resultado = retorna_datos_variable(
      *   "SELECT  * FROM usuarios WHERE activo = 1",
      *   "mi_plataforma",
      *   __FILE__
      * );
      * print_r($resultado['data']); // Muestra los datos obtenidos
      * echo $resultado['rowCount']; // Muestra el número de filas afectadas
   */
    function retorna_datos_variable($sentencia,$plataforma = DB_SYSTEM,$direccion = "") {
      //Se manda a llamar el archivo de bases de datos del entorno
        include A_DATABASES_ENV;
      //Validar que la plataforma existe en la configuración
        if (!isset($bd_env[$plataforma])) {
          $error = "Plataforma '$plataforma' no configurada en A_DATABASES_ENV";
          $archivo = __FILE__."::Funcion retorna_datos_variable";
          escribir_log($error, "Validación de plataforma", $archivo);
          die();
        }
      //Obtengo los datos de la plataforma a la que se va a conectar 
        $user=campo_limpiado($bd_env[$plataforma]['USER'],2,0,1,"USER");
        $host=campo_limpiado($bd_env[$plataforma]['HOST'],2,0,1,"HOST");
        $dbname=campo_limpiado($bd_env[$plataforma]['DB'],2,0,1,"DB");
        $port=campo_limpiado($bd_env[$plataforma]['PORT'],2,0,1,"PORT");
        $passwd=campo_limpiado($bd_env[$plataforma]['PASS'],2,0,0,"PASS");
      //Se incluye el archivo de conexion a la base de datos variable
        include A_VARIABLE_CONNECTOR;
      //Intento ejecutar la sentencia. Se comienza con la ejecución
        try {
          //Preparo la sentencia a ejecutar
            $sql = $conn->prepare($sentencia);
          //Ejecutar la sentencia
            $sql->execute();
          // Obtener el número de filas afectadas
            $rowCount = $sql->rowCount();
          // Obtener los datos de la tabla
            $datos = array();
            while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
              $datos[] = $fila;
            }
          // Cerrar el cursor
            $sql->closeCursor();
          // Retornar el resultado
            return array('data' => $datos, 'rowCount' => $rowCount);;
          //
        } catch (PDOException $e) {
          //Almaceno el error en una variabLe
            $error=$e->getMessage();
          //Verifico si se incluyo una direccion de error
            if (campo_limpiado($direccion,2)!="") {
              $agregado=", Referenciado desde $direccion";
            }else{
              $agregado=Null;
            }
          //Ubico el archivo desde donde se presenta el error
            $archivo=__FILE__."::Funcion retorna_datos_variable$agregado";
          //Mando a escribir el mensaje
            escribir_log($error,$sentencia,$archivo);
          //Detengo el procedimiento
            die();
          //
        } finally {
          // Cerrar la conexión
            if (isset($sql)) {
              $sql = null;
            }
            if (isset($conn)) {
              $conn = null;
            }
          //
        }
      //
    }
  //Función para buscar la cantidad de registros existentes en BD variable
    /**
      * Busca la existencia de registros en la base de datos.
      *
      * @param string $sentencia La sentencia SQL a ejecutar.
      * @param string $plataforma La plataforma de base de datos a la que se conectará, si no se define una, se usará DB_SYSTEM.
      * @param string $direccion (Opcional) Ruta del archivo que invoca la función para depuración.
      * @return int Cantidad de registros existentes según la sentencia SQL.
      * 
      * @example
      * $existencia = busca_existencia_variable(
      *   "SELECT COUNT(*) AS exist FROM usuarios WHERE activo = 1",
      *   "mi_plataforma",
      *   __FILE__
      * );
      * echo $existencia; // Muestra la cantidad de usuarios activos
   */
    function busca_existencia_variable($sentencia,$plataforma = DB_SYSTEM,$direccion = "") {
      //Se manda a llamar el archivo de bases de datos del entorno
        include A_DATABASES_ENV;
      //Validar que la plataforma existe en la configuración
        if (!isset($bd_env[$plataforma])) {
          $error = "Plataforma '$plataforma' no configurada en A_DATABASES_ENV";
          $archivo = __FILE__."::Funcion busca_existencia_variable";
          escribir_log($error, "Validación de plataforma", $archivo);
          die();
        }
      //Obtengo los datos de la plataforma a la que se va a conectar
        $user=campo_limpiado($bd_env[$plataforma]['USER'],2,0,1,"USER");
        $host=campo_limpiado($bd_env[$plataforma]['HOST'],2,0,1,"HOST");
        $dbname=campo_limpiado($bd_env[$plataforma]['DB'],2,0,1,"DB");
        $port=campo_limpiado($bd_env[$plataforma]['PORT'],2,0,1,"PORT");
        $passwd=campo_limpiado($bd_env[$plataforma]['PASS'],2,0,0,"PASS");
      //Se incluye el archivo de conexion a la base de datos variable
        include A_VARIABLE_CONNECTOR;
      //Ejecucion del proceso
        try {
          //Preparo la sentencia a ejecutar
            $sql = $conn->prepare($sentencia);
          //Ejecutar la sentencia
            $sql->execute();
          //Asocio los datos de la tabla obtenidos
            $tabla=$sql->fetch(PDO::FETCH_ASSOC);
          //Retorna el valor obtenido
            return $tabla['exist'];
          //finalizo el cursor
            $sql->CloseCursor();
          //
        } catch (PDOException $e) {
          //Almaceno el error en una variabLe
            $error=$e->getMessage();
          //Verifico si se incluyo una direccion de error
            if (campo_limpiado($direccion,2)!="") {
              $agregado=", Referenciado desde $direccion";
            }else{
              $agregado=Null;
            }
          //Ubico el archivo desde donde se presenta el error
            $archivo=__FILE__."::Funcion busca_existencia_variable$agregado";
          //Mando a escribir el mensaje
            escribir_log($error,$sentencia,$archivo);
          //Detengo el procedimiento
            die();
          //
        } finally {
          // Cerrar la conexión
            if (isset($sql)) {
              $sql = null;
            }
            if (isset($conn)) {
              $conn = null;
            }
          //
        }
      //
    }
  //Función para ejecutar sentencias dentro de la base de datos variable
    /**
      * Ejecuta una sentencia SQL en la base de datos.
      *
      * @param string $sentencia La sentencia SQL a ejecutar.
      * @param string $plataforma La plataforma de base de datos a la que se conectará, si no se define una, se usará DB_SYSTEM.
      * @param string $mensaje El mensaje a retornar si la ejecución es exitosa.
      * @param string $direccion (Opcional) Ruta del archivo que invoca la función para depuración.
      * @return string El mensaje proporcionado si la ejecución es exitosa.
      * 
      * @example
      * $resultado = ejecuta_sentencia_variable(
      *   "UPDATE usuarios SET activo = 1 WHERE id = 123",
      *   "mi_plataforma",
      *   "Usuario activado correctamente",
      *   __FILE__
      * );
      * echo $resultado; // Muestra: Usuario activado correctamente
   */
    function ejecuta_sentencia_variable($sentencia,$plataforma = DB_SYSTEM,$mensaje,$direccion = "") {
      //Se manda a llamar el archivo de bases de datos del entorno
        include A_DATABASES_ENV;
      //Validar que la plataforma existe en la configuración
        if (!isset($bd_env[$plataforma])) {
          $error = "Plataforma '$plataforma' no configurada en A_DATABASES_ENV";
          $archivo = __FILE__."::Funcion ejecuta_sentencia_variable";
          escribir_log($error, "Validación de plataforma", $archivo);
          die();
        }
      //Obtengo los datos de la plataforma a la que se va a conectar
        $user=campo_limpiado($bd_env[$plataforma]['USER'],2,0,1,"USER");
        $host=campo_limpiado($bd_env[$plataforma]['HOST'],2,0,1,"HOST");
        $dbname=campo_limpiado($bd_env[$plataforma]['DB'],2,0,1,"DB");
        $port=campo_limpiado($bd_env[$plataforma]['PORT'],2,0,1,"PORT");
        $passwd=campo_limpiado($bd_env[$plataforma]['PASS'],2,0,0,"PASS");
      //Se incluye el archivo de conexion a la base de datos variable
        include A_VARIABLE_CONNECTOR;
      //Ejecucion del proceso
        try {
          //Preparo la sentencia a ejecutar
            $sql=$conn->prepare($sentencia);
          //ejecuto la sentencia
            $res=$sql->execute();
          //finalizo el cursor
            $sql->CloseCursor();
          //Verifico el resultado de la ejecución
            if ($res) {
              //Imprime el mensaje si la ejecución fue exitosa
                return $mensaje;
              //
            } else {
              //Lanza una excepción si la ejecución falla
                throw new Exception("Error al ejecutar la sentencia.");
              //
            }
          //
        } catch (PDOException $e) {
          //Almaceno el error en una variabLe
            $error=$e->getMessage();
          //Verifico si se incluyo una direccion de error
            if (campo_limpiado($direccion,2)!="") {
              $agregado=", Referenciado desde $direccion";
            }else{
              $agregado=Null;
            }
          //Ubico el archivo desde donde se presenta el error
            $archivo=__FILE__."::Funcion ejecuta_sentencia_variable$agregado";
          //Mando a escribir el mensaje
            escribir_log($error,$sentencia,$archivo);
          //Detengo el procedimiento
            die();
          //
        } finally {
          // Cerrar la conexión
            if (isset($sql)) {
              $sql = null;
            }
            if (isset($conn)) {
              $conn = null;
            }
          //
        }
      //
    }
  //
?>