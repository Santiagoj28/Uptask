<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email {
    protected $email;
    protected $nombre;
    protected $token;
    public function __construct($email , $nombre , $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USERNAME'];
        $mail->Password = $_ENV['EMAIL_PASS'] ;
        $mail->SMTPSecure = 'tls';

        $mail->setFrom("Cuentas@uptask.com","Uptask");
        $mail->addAddress("Cuentas@uptask.com","Uptask.com");
        $mail->Subject = "Confirma tu cuenta";
         //set html
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
 
         $contenido ="<html>";
         $contenido.= "<p><strong>Hola " . $this->nombre ."</strong>  Has Creado tu cuenta en Uptask,confirma tu cuenta presionando el siguiente enlance</p>";
         $contenido .= "<p>Presiona aqui: <a href='".$_ENV['APP_URL'] ."/confirmar?token=".$this->token."'>Confirma tu cuenta</a> <p>";
         $contenido.= "<p>Si tu no solicitaste crear esta cuenta puedes ignorar el mensaje.</p>";
         $contenido.="</html>";

         $mail->Body = $contenido;

         if($mail->send()){
            $mensaje = 'Hubo un Error... intente de nuevo';
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    public function restablecerPassword(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USERNAME'];
        $mail->Password = $_ENV['EMAIL_PASS'] ;
        $mail->SMTPSecure = 'tls';

        $mail->setFrom("Cuentas@uptask.com","Uptask");
        $mail->addAddress("Cuentas@uptask.com","Uptask.com");
        $mail->Subject = "Restablece tu password";
         //set html
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
 
         $contenido ="<html>";
         $contenido.= "<p><strong>Hola " . $this->nombre ."</strong>  Has solicitado cambiar tu password presiona el siguiente enlace para hacerlo</p>";
         $contenido .= "<p>Presiona aqui: <a href='".$_ENV['APP_URL'] ."/restablecer_password?token=".$this->token."'>Confirma tu cuenta</a> <p>";
         $contenido.= "<p>Si tu no solicitaste crear esta cuenta puedes ignorar el mensaje.</p>";
         $contenido.="</html>";

         $mail->Body = $contenido;

         if($mail->send()){
            $mensaje = 'Hubo un Error... intente de nuevo';
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

}