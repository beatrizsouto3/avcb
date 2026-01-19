<?php
    session_start();

    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {
        include_once('config.php');

        $email = $_POST['email'];
        $senha = md5($_POST['senha']);

        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha
        ]);

        if($stmt->rowCount() < 1)
        {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            $_SESSION['nao_autenticado'] = true;
            
            header('Location: login.php');
        }
        else
        {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['email'] = $email;
            $_SESSION['id_usuario'] = $usuario['id']; 
            $_SESSION['permissao'] = $usuario['permissao_id']; 
            
            if($usuario['primeiro_acesso'] == true || $usuario['primeiro_acesso'] == 't'){
                header('Location: novaSenha.php');
            } 
            else {
                $_SESSION['senha'] = $senha;
                header('Location: sistema.php');
            }
        }
    }
    else
    {
        header('Location: login.php');
    }
?>