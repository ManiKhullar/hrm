<?php

namespace App\Http\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class SendMail
{
    public static function sendMail($body, $subject, $to, $from, $cc)
    {    
      
      $mail = new PHPMailer(true);
      if($cc!=""){
        $cc =  $cc.',pramod@bluethink.in';
      }else{
        $cc =  'pramod@bluethink.in';
      }
      $ccArray = explode(',',$cc);
      try {
          // SMTP Configuration
          $mail->isSMTP();
          $mail->Host       = env('MAIL_HOST', 'smtp.example.com');
          $mail->SMTPAuth   = true;
          $mail->Username   = env('MAIL_USERNAME', 'your_email@example.com');
          $mail->Password   = env('MAIL_PASSWORD', 'your_password');
          $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
          $mail->Port       = env('MAIL_PORT', 587);
          if (!empty($ccArray)) {
            foreach ($ccArray as $ccEmail) {
                $mail->addCC($ccEmail);
            }
        }
          // Sender and Recipient
          $mail->setFrom($from ?? env('MAIL_FROM_ADDRESS', 'default@example.com'), $fromName ?? env('MAIL_FROM_NAME', 'Default Sender'));
          $mail->addAddress($to);

          // Email Content
          $mail->isHTML(true);
          $mail->Subject = $subject;
          $mail->Body    = $body;
          $mail->AltBody = strip_tags($body);

          // Send Email
          $mail->send();

          return true;
      } catch (Exception $e) {
          return "Mailer Error: {$mail->ErrorInfo}";
      }
  }
}