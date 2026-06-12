<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  // Mando a llamar la conexion a la BD
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  include_once A_CONNECTION;
  //Creeo una funcion para realizar el respaldo
    function backupDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName, $tables = '*'){
      // Conexión a la base de datos
        $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
      // Verifica la conexión
        if ($db->connect_error) {
          //Escribe un mensaje alertando que no es posible la conexion a la BD
          die("Error de conexión a la base de datos: " . $db->connect_error);
        }
      // Obtngo todas las tablas de labase de datos
        if ($tables == '*') {
          //Creeo un arreglo para almacenar los nombres de las tablas
            $tables = array();
          //Realizo y ejecuto una sentencia que me muestre todas las tablas de la base de datos
            $result = $db->query("SHOW TABLES");
          //Mientras existan datos obtenidos de la consulta
            while ($row = $result->fetch_row()) {
              //Se almacena el nombre de la tabla en el arreglo creado anteriormente
              $tables[] = $row[0];
            }
          //
        } else {
          //asegura que independientemente de si la variable $tables es un valor único o una lista separada por comas, al final tendremos un arreglo de nombres de tablas
          $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
      //Se define una variable vacia
        $return = '';
      // Recorre las tablas
        foreach ($tables as $table) {
          //Se extraen todos las filas de la tabla en turno
          $result = $db->query("SELECT * FROM $table");
          //Se obtiene la cantidad de columnas de la tabla
          $numColumns = $result->field_count;
          //Se escribe la primera sentencia del archivo, definiendo que si la tabla exite se elimine para reemplazarla por la nueva tabla
          $return .= "DROP TABLE IF EXISTS $table;\n";
          //Se define y ejecuta una sentencia para mostrar el codigo de creacion de la tabla en turno
          $result2 = $db->query("SHOW CREATE TABLE $table");
          //Se asignan todas las variables obtenida al arreglo correspondiente
          $row2 = $result2->fetch_row();
          //Se almacena en la vriable de escritura el codigo de creacion de la tabla
          $return .= $row2[1] . ";\n\n";
          //Se realiza un ciclo para recorrer cada columna obtenida de la tabla
          for ($i = 0; $i < $numColumns; $i++) {
            //Asigno cada valor obtenido de la consulta a los datos de la tabla en una variable 
            while ($row = $result->fetch_row()) {
              //Inicializo la sentencia de insercion de lo datos en la variable de estritura.
              $return .= "INSERT INTO $table VALUES(";
              //Defino un ciclo for para recorrer todas las columnas de la tabla
              for ($j = 0; $j < $numColumns; $j++) {
                //Almaceno el valor de esa columna despues de haber limpiado el contenido
                $row[$j] = $db->real_escape_string($row[$j]);
                //Se reemplazan los saltos de linea
                $row[$j] = str_replace("\n", "\\n", $row[$j]);
                //Se evalua si el valor de la columna se encuentra declarado o vacio
                if (isset($row[$j])) {
                  //Se concatena el valor en la variable de escritura
                  $return .= '"' . $row[$j] . '"';
                } else {
                  //Se ingresa una valor de columna vacio en la tabla
                  $return .= '""';
                }
                //Si la posicion actual del ciclo es menor a la cantidad de columnas, se agrega un separador de columna (,)
                if ($j < ($numColumns - 1)) { $return .= ','; }
              }
              //Se finaliza la sentencia de escritura concatenando un parentesis de cierre y punto y coma en la variable de escritura;
              $return .= ");\n";
            }
          }
          //Se concatena un salto de linea en la variable de escritura
          $return .= "\n";
        }
      //Se define el archivo de repaldo con su ruta
        $backupFileName = A_RESTORE . ref_fecha() . NAME_DB . ".sql";
      //Se escribe el contenido de la variable de escritura en el archivo 
        file_put_contents($backupFileName, $return);
      //Escribo un mensaje de confirmacion
        echo "<script>alert('La base de datos ha sido respaldada en el archivo: $backupFileName');</script>";
      //
    }
  //Mando a llamar la funcion para realizar el respaldo
    try{
      backupDatabaseTables(HOST_DB, USER_DB, PASSWRD_DB, NAME_DB);
    } catch (Exception $e) {
    //Almaceno el error en una variabLe
    $error=$e->getMessage();
    //Ubico el archivo desde donde se presenta el error
    $archivo=__FILE__;
    $sentencia="FUNCION:backupDatabaseTables(HOST_DB, USER_DB, PASSWRD_DB, NAME_DB)";
    //Mando a escribir el mensaje
    escribir_log($error,$sentencia,$archivo);
    //Detengo el procedimiento
    die();
  }
  //
?>