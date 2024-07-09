<?php
   namespace App\Utility;
   
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;


   require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
   require_once  '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
   require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

   
   class Email{
    
    static function sendMail($recv="videgrenierBloc5@outlook.com", $content="Nouveau message", $title="Quelqu'un vous a envoyé un message pour un de vos articles"){


   $mail = new PHPMailer(true);
   
   $mail->SMTPOptions = array(
    'tls' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );
    $mail->CharSet = 'UTF-8';

   $mail->SMTPDebug = 0;

   $mail->isSMTP();

   $mail->Host = 'smtp.office365.com';

   $mail->SMTPAuth = true;

   $mail->Username = "videgrenierBloc5@outlook.com";

   $mail->Password = "AZERTY12345";

   $mail->SMTPSecure = "tls";

   $mail->Port = 587;

   $mail->From = "videgrenierBloc5@outlook.com";

   $mail->FromName = "VideGrenierBloc5";

   
   $mail->addAddress($recv);

   $mail->isHTML(true);

   $mail->Subject = $title;

   $mail->Body =  $content;

   $mail->AltBody = "This is the plain text version of the email content";


   try {

       $mail->send();

       echo "Message has been sent successfully";

   } catch (Exception $e) {

       echo "Mailer Error: " . $mail->ErrorInfo;

   }
    }}
?>