<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function getMailer() {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html'; 

        $mail->isSMTP();
        
        $mail->Host       = '---'; 
        $mail->SMTPAuth   = true;
        
        $mail->Username   = '---'; 
        $mail->Password   = '---';
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465; 

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom('---', 'Sistema AVCB');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        return $mail;
    } catch (Exception $e) {
        return null;
    }
}

function enviarEmailBoasVindas($nome, $emailDestino, $senhaLimpa) {
    $mail = getMailer();
    if($mail) {
        try {
            $mail->addAddress($emailDestino, $nome);
            $mail->Subject = 'Bem-vindo(a) ao Sistema AVCB';
            
            $linkSistema = "http://localhost/avcb/login.php"; 

            $mail->Body = "
            <div style='background-color: #f4f4f4; padding: 40px 0; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; padding: 40px; border: 1px solid #ddd; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
                    <h2 style='color: #2E8B57; text-align: center; margin-top: 0;'>Bem-vindo(a), $nome!</h2>
                    <p style='color: #555; font-size: 16px; text-align: center;'>Seu cadastro foi realizado com sucesso.</p>
                    <div style='border: 2px dashed #2E8B57; background-color: #fafffa; padding: 20px; text-align: center; margin: 30px 0; border-radius: 8px;'>
                        <span style='font-size: 24px; font-weight: bold; color: #333;'>$senhaLimpa</span>
                    </div>
                    <div style='text-align: center; margin-bottom: 30px;'>
                        <a href='$linkSistema' style='background-color: #2E8B57; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Acessar Sistema Agora</a>
                    </div>
                </div>
            </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}

function enviarEmailRecuperacao($nome, $emailDestino, $senhaTemp) {
    $mail = getMailer();
    if($mail) {
        try {
            $mail->addAddress($emailDestino, $nome);
            $mail->Subject = 'Recuperação de Senha - Sistema AVCB';
            
            $linkSistema = "http://localhost/avcb/login.php";

            $mail->Body = "
            <div style='background-color: #f4f4f4; padding: 40px 0; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; padding: 40px; border: 1px solid #ddd; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
                    <h2 style='color: #d35400; text-align: center; margin-top: 0;'>Recuperação de Senha</h2>
                    <p style='color: #555; font-size: 16px; text-align: center;'>Sua nova senha temporária:</p>
                    <div style='border: 2px dashed #d35400; background-color: #fff5f0; padding: 20px; text-align: center; margin: 30px 0; border-radius: 8px;'>
                        <span style='font-size: 24px; font-weight: bold; color: #333;'>$senhaTemp</span>
                    </div>
                    <div style='text-align: center; margin-bottom: 30px;'>
                        <a href='$linkSistema' style='background-color: #d35400; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Fazer Login</a>
                    </div>
                </div>
            </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}
?>