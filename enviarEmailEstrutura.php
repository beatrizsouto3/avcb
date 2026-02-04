<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function enviarEmailBoasVindas($nomeUsuario, $emailUsuario, $senhaTemporaria) {
    $mail = new PHPMailer(true);
    
    $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    
    $host = $_SERVER['HTTP_HOST'];
    
    $link_sistema = $protocolo . "://" . $host . "/avcb/login.php";

    //dados smtp
    $host_smtp = '---'; 
    $email_remetente = '---';
    $senha_email     = '---';
    $porta_smtp      = 465;

    try {
        $mail->isSMTP();
        $mail->Host       = $host_smtp;
        $mail->SMTPAuth   = true;
        $mail->Username   = $email_remetente;
        $mail->Password   = $senha_email;
        
        if($porta_smtp == 465){
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        
        $mail->Port       = $porta_smtp;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($email_remetente, 'Sistema AVCB');
        $mail->addAddress($emailUsuario, $nomeUsuario);

        $mail->isHTML(true);
        $mail->Subject = 'Senha de Acesso - Sistema AVCB';
        
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                <h2 style='color: #28a745; text-align: center;'>Bem-vindo(a), $nomeUsuario!</h2>
                
                <p style='font-size: 16px;'>Seu cadastro foi realizado com sucesso.</p>
                <p>Para acessar o sistema, utilize a senha temporária abaixo:</p>
                
                <div style='background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 2px; border: 2px dashed #28a745; margin: 20px 0; color: #333;'>
                    $senhaTemporaria
                </div>

                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$link_sistema' style='background-color: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; display: inline-block;'>
                        Acessar Sistema Agora
                    </a>
                </div>
                
                <p style='font-size: 12px; color: #888; text-align: center;'>
                    Link de acesso: <a href='$link_sistema' style='color: #28a745;'>$link_sistema</a>
                </p>

                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>Não compartilhe esta senha com ninguém.<br>Este é um e-mail automático.</p>
            </div>
        ";
        
        $mail->AltBody = "Olá $nomeUsuario. Sua senha temporária é: $senhaTemporaria. Acesse em: $link_sistema";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>