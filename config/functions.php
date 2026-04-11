<?php

declare(strict_types=1);

// Hàm gửi email (PHPMailer manual 3 file gốc trong includes/Mailer).
require_once __DIR__ . '/../includes/Mailer/Exception.php';
require_once __DIR__ . '/../includes/Mailer/PHPMailer.php';
require_once __DIR__ . '/../includes/Mailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail(string $emailTo, string $subject, string $content): bool
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'manu210924@gmail.com';                     //SMTP username
        $mail->Password   = 'wypwtdxwxjzclwjf';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('manu210924@gmail.com', 'Hieule course');
        $mail->addAddress($emailTo);     //Add a recipient


        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->SMTPOptions = array(
            'ssl' => [
                'verify_peer' => true,
                'verify_depth' => 3,
                'allow_self_signed' => true,
            ],
        );

        return (bool)$mail->send();
    } catch (Exception $e) {
        // Không echo ở đây để tránh phá layout/redirect; caller sẽ tự xử lý.
        return false;
    }
}
