<?php
/*
* https://nicholasleite.wordpress.com/2013/04/10/dica-implementando-o-phpmailer-no-codeigniter/
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_phpmailer
{
    public function __construct()
    {
        require_once('PHPMailer-5.2.10/PHPMailerAutoload.php');
    }

    public function enviarEmail($destino_nome, $destino_email, $assunto, $corpo, $suporte, $reply_nome = '', $reply_email = '',$cco = array())
    {
        $retorno = 0;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->SMTPAuth = false;
        $mail->Host = "10.1.1.27";
        $mail->Port = 25;
        $mail->Username = 'naoresponda@satc.edu.br';
        $mail->Password = 'SATCmail123';
        /*$mail->SMTPAuth = true;
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;
        $mail->Username = 'naoresponda@satc.edu.br';
        $mail->Password = 'SATCmail123';*/

        if ($suporte==1) {
            //$destino_email = 'salesio.silva@satc.edu.br';
            //$destino_email = 'ramires.oliveira@satc.edu.br';
            //$destino_email = 'alexandre.luz@satc.edu.br';
            //$destino_email = 'mateus.gamba@satc.edu.br';

            $destino_email = 'thiago.zimmermann@satc.edu.br';
            //$destino_email = 'brayan.bertan@satc.edu.br';
            // $destino_email = 'thiago.zimmermann@satc.edu.br';
            //$destino_email = 'brayan.bertan@satc.edu.br';
            //$destino_email = 'leticia.cardozo@satc.edu.br';
            //$destino_email = 'anderson.rosa@satc.edu.br';
            //$destino_email = 'cletson.menegon@satc.edu.br';
        }

        if ($reply_nome=='' || $reply_email=='') {
            $reply_email = $destino_nome;
            $reply_nome = $destino_email;
        }

        $mail->AddReplyTo($reply_email, $reply_nome);

        if (count($cco)>0) {
            foreach ($cco as $email => $name) {
                $mail->addBCC($email, $name);
            }
        }

        $mail->SetFrom('naoresponda@satc.edu.br', 'Não Responda');
        $mail->Subject = $assunto;
        $mail->Body = $corpo;

        $mail->AddAddress($destino_email, $destino_nome);

        if (!$mail->Send()) {
            echo "Ocorreu um erro durante o envio: " . $mail->ErrorInfo;
        } else {
            $retorno = 1;
        }
        return $retorno;
    }

}
