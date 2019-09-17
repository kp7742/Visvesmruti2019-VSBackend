<?php
//api url filter
if (strpos($_SERVER['REQUEST_URI'], "mailer.php")) {
    require_once 'utils.php';
    PlainDie();
}

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($eMail, $subject, $data){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = '<HOST>';
        $mail->SMTPAuth = true;
        $mail->Username = '<USER>';
        $mail->Password = '<PASS>';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@visvesmruti.tech', 'Visvesmruti2K19');
        $mail->addAddress($eMail);
        $mail->addCC('no-reply@visvesmruti.tech');

        $mail->Subject = $subject;
        $mail->Body = $data;

        $mail->send();
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
}

function sendMailWithAttach($eMail, $subject, $data, $attachname, $attachpath){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = '<HOST>';
        $mail->SMTPAuth = true;
        $mail->Username = '<USER>';
        $mail->Password = '<PASS>';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@visvesmruti.tech', 'Visvesmruti2K19');
        $mail->addAddress($eMail);
        $mail->addCC('no-reply@visvesmruti.tech');

        $mail->addAttachment($attachpath, $attachname);

        $mail->Subject = $subject;
        $mail->Body = $data;

        $mail->send();
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
}