<?php
    if (session_status() === PHP_SESSION_NONE) {session_start();}
    include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';

    // Configuración de la base de datos
    $host = HOST_DB;
    $dbname = NAME_DB;
    $username = USER_DB;
    $password = PASSWRD_DB;
    //Obtengo la fecha actual
    $fecha=ahora(1);

    try {
        // Conexión a la base de datos usando PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener todas las tablas de la base de datos
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $backupSql = "-- Copia de seguridad de la base de datos: $dbname\n";
        $backupSql .= "-- Fecha: $fecha\n\n";

        foreach ($tables as $table) {
            // Obtener la estructura de la tabla
            $createTableStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $backupSql .= "-- Estructura de la tabla `$table`\n";
            $backupSql .= $createTableStmt['Create Table'] . ";\n\n";

            // Obtener los datos de la tabla
            $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $backupSql .= "-- Datos de la tabla `$table`\n";
                foreach ($rows as $row) {
                    $values = array_map(function ($value) use ($pdo) {
                        return is_null($value) ? 'NULL' : $pdo->quote($value);
                    }, $row);
                    $backupSql .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                }
                $backupSql .= "\n";
            }
        }

        // Guardar el archivo de copia de seguridad
        $backupFile = 'backup_' . $dbname . " - $fecha.sql";
        file_put_contents($backupFile, $backupSql);

        // Forzar la descarga del archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        readfile($backupFile);

        // Eliminar el archivo después de la descarga
        unlink($backupFile);

    } catch (PDOException $e) {
        echo "Error al realizar la copia de seguridad: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $pdo = null;
    }
?>