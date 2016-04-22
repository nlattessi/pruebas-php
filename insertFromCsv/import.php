<?php

if (isset($_POST['Submit'])) {
    if (is_uploaded_file($_FILES['csv']['tmp_name'])) {
        try {
            $servername = "localhost";
            $username = "root";
            $password = "ASDcxz111";
            $dbname = "choferes";

            //connect to the database
            $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            //

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_FILES['csv']['size'] > 0) {

                //get the csv file
                $file = $_FILES['csv']['tmp_name'];
                $handle = fopen($file,"r");

                //loop through the csv file and insert into database
                do {
                    if ($data[0]) {
                        $stmt = $conn->prepare("INSERT INTO chofer (nombre, apellido, dni, tiene_curso_basico) SELECT * FROM (SELECT :nombre, :apellido, :dni, 1) AS tmp WHERE NOT EXISTS (SELECT dni FROM chofer WHERE dni = :dni2) LIMIT 1");
                        $stmt->bindParam(':nombre', trim($data[0]));
                        $stmt->bindParam(':apellido', trim($data[1]));
                        $stmt->bindParam(':dni', trim($data[2]));
                        $stmt->bindParam(':dni2', trim($data[2]));
                        //$stmt->bindValue(':tieneCursoBasico', true, \PDO::PARAM_BOOL);
                        $stmt->execute();
                        error_log("cargado: " . $data[2], 0);
                    }
                } while ($data = fgetcsv($handle,1000,",","'"));
                //

                $conn = null;

                //redirect
                header('Location: import.php?success=1'); die;

            }
        } catch (\PDOException $e) {
            print "ERROR!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Import a CSV File with PHP & MySQL</title>
</head>

<body>

<?php if (!empty($_GET['success'])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your file: <br />
  <input name="csv" type="file" id="csv" />
  <input type="submit" name="Submit" value="Submit" />
</form>

</body>
</html>