<?php

    session_start();

    //print_r($_REQUEST);

use PSpell\Config;

    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])){

        // acessa
        include_once('config.php');

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha
        ]);

        // conta quantas linhas o banco devolveu
        if($stmt->rowCount() < 1)
        {
            // menor que 1 = não achou ninguem
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: login.php');
        }
        else
        {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            
            print_r($usuario);
            
            header('Location: sistema.php');
        }
    } else {
        //não acessa
        header('Location: login.php');
    }



?>