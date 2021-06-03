<?php

/**
 * This example shows how to handle a simple contact form safely.
 */

//Import PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

$msg = '';
//Don't run this unless we're handling a form submission
if (array_key_exists('email', $_POST)) {
    date_default_timezone_set('Etc/UTC');

    //Create a new PHPMailer instance
    $mail = new PHPMailer();

    //Send using SMTP to localhost (faster and safer than using mail()) – requires a local mail server
    // SERVER GOES HERE
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                            // OR STARTTLS
    $mail->Host = 'SECRET';                                                     // SECRET                                                          
    $mail->Port = 587;                                                          // SECRET
    $mail->Username = 'SECRET';                                                 // SECRET
    $mail->setFrom('SECRET', 'SECRET');                                         // SECRET
    $mail->Password = 'SECRET';                                                 // SECRET

    $mail->addAddress('SECRET');                                                // SECRET

    // Put the submitter's address in a reply-to header
    // This will fail if the address provided is invalid,
    // in which case we should ignore the whole request
    if ($mail->addReplyTo($_POST['email'], $_POST['name']) && $_POST['checksum'] == "Brotzeit") {
        $mail->Subject = 'SOME SUBJECT';                                        // MODIFY
        //Keep it simple - don't use HTML
        $mail->isHTML(false);
        //Build a simple message body
        $mail->Body = <<<EOT
Email: {$_POST['email']}
Vorname : {$_POST['first_name']}
Nachname: {$_POST['last_name']}
Message: {$_POST['message']}
EOT;
        //Send the message, check for errors
        if (!$mail->send()) {
            //The reason for failing to send will be in $mail->ErrorInfo
            //but it's unsafe to display errors directly to users - process the error, log it on your server.
            $msg = 'Ihre Anfrage konnte nicht bearbeitet werden. Bitte nutzen Sie eine andere Möglichkeit, uns zu kontaktieren.';
        } else {
            $msg = 'Ihre Anfrage wurde bearbeitet. Die E-Mail wurde erfolgreich verschickt.';
        }
    } else {
        $msg = 'Ihre Anfrage konnte nicht bearbeitet werden, da ihre E-Mail-Adresse nicht existiert. Bitte nutzen Sie eine andere Möglichkeit, uns zu kontaktieren.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kontaktformular</title>
</head>
<body>
<?php if (!empty($msg)) {
    echo "<h2>$msg</h2>";
} ?>
<a href="/"><h2>Zur Startseite</h2></a>
</body>
</html>
