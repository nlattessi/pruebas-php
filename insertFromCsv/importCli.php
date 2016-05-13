<?php

if (($handle = fopen("choferes.csv", "r")) !== FALSE) {
    try {
        $servername = "localhost";
        $username = "root";
        $password = "ASDcxz111";
        $dbname = "choferes";

        $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($data[0]) {
                $stmt = $conn->prepare("INSERT INTO chofer (nombre, apellido, dni, tiene_curso_basico) SELECT * FROM (SELECT :nombre as nombre, :apellido as apellido, :dni, 1) AS tmp WHERE NOT EXISTS (SELECT ch.dni FROM chofer as ch WHERE ch.dni = :dni2) LIMIT 1");
                $stmt->bindParam(':nombre', trim($data[0]));
                $stmt->bindParam(':apellido', trim($data[1]));
                $stmt->bindParam(':dni', trim($data[2]));
                $stmt->bindParam(':dni2', trim($data[2]));
                $stmt->execute();
                echo "procesado dni: " . $data[2] . "\n";
            }
        }

        $conn = null;
        echo "fin de procesamiento";
    } catch (\PDOException $e) {
        print "ERROR!: " . $e->getMessage();
        die();
    }
}
