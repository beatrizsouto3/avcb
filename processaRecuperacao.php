<?php
    session_start();
    include_once('config.php');
    include_once('enviarEmail.php');

    if(isset($_POST['submit'])){
        $email = $_POST['email'];

        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        if($stmt->rowCount() > 0){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $senhaTemporaria = substr(md5(time()), 0, 6);
            $senhaHash = md5($senhaTemporaria);

            $sqlUpdate = "UPDATE usuarios SET senha = :senha, primeiro_acesso = 'true' WHERE id = :id";
            $stmtUp = $pdo->prepare($sqlUpdate);
            $stmtUp->execute([':senha' => $senhaHash, ':id' => $user['id']]);

            if(enviarEmailRecuperacao($user['nome'], $email, $senhaTemporaria)){
                header('Location: login.php?msg=recuperacao_ok');
                exit;
            } else {
                header('Location: esqueciSenha.php?msg=erro_envio');
                exit;
            }
        } else {
            header('Location: esqueciSenha.php?msg=email_nao_encontrado');
            exit;
        }
    } else {
        header('Location: login.php');
        exit;
    }
?>