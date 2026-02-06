<?php
session_start();
include_once('config.php');

if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
{
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senhaMd5 = md5($senha);

    $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email, ':senha' => $senhaMd5]);

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
        
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        $_SESSION['permissao'] = $usuario['permissao_id'];
        
        header('Location: sistema.php');
    }
}
else
{
    header('Location: login.php');
}
?>