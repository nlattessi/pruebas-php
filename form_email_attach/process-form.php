<?php

require 'lib/PHPMailer/PHPMailerAutoload.php';

$name_of_attach = basename($_FILES['attachment']['name']);
$type_of_attach = substr($name_of_attach, strpos($name_of_attach, '.') + 1);
$size_of_attach = $_FILES['attachment']['size'] / 1024;

// Settings
$max_allowed_file_size = 100;
$allowed_extensions = ['jpg', 'jpeg', 'gif', 'bmp'];
$upload_folder = 'uploads/';

// Validations
if ($size_of_attach > $max_allowed_file_size) {
    $errors .= "\n Size of file should be less than $max_allowed_file_size";
}

$allowed_ext = false;
for ($i = 0; $i < sizeof($allowed_extensions); $i++) {
    if (strcasecmp($allowed_extensions[$i], $type_of_attach) == 0) {
        $allowed_ext = true;
    }
}

if (!$allowed_ext) {
    $errors .= "\n The uploaded file is not supported file type. "
        . " Only the following file types are supported: " . implode(', ', $allowed_extensions);
}

$path_of_attach = $upload_folder . $name_of_attach;
$tmp_path = $_FILES['attachment']['tmp_name'];
if (is_uploaded_file($tmp_path)) {
    if (!copy($tmp_path, $path_of_attach)) {
        $errors .= "\n Error while copying the uploaded file";
    }
}

if (isset($errors)) {
    echo $errors . '\n\n';
    exit();
}

$address = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$mail = new PHPMailer;

// $mail->SMTPDebug = 3;

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = ''; // LLENAR CON USUARIO DE GMAIL
$mail->Password = ''; // LLENAR CON PASSWORD DE GMAIL
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('email', ''); // REEMPLAZAR CON EMAIL Y NOMBRE DEL FROM
$mail->addAddress($address);

$mail->addAttachment($path_of_attach);
$mail->isHTML(true);

$mail->Subject = $subject;
$mail->Body    = $message;
$mail->AltBody = strip_tags($message);

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
