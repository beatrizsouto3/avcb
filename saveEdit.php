<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 1)){
        header('Location: sistema.php');
        exit;
    }

    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $telefone = $_POST['telefone'];
        $sexo = $_POST['genero'];
        $data_nascimento = $_POST['data_nascimento'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $endereco = $_POST['endereco'];
        
        $permissao_id = $_POST['permissao_id']; 
        
        $sql = "UPDATE usuarios 
                SET nome = :nome, 
                    email = :email, 
                    senha = :senha, 
                    telefone = :telefone, 
                    sexo = :sexo, 
                    data_nascimento = :data_nascimento, 
                    cidade = :cidade, 
                    estado = :estado, 
                    endereco = :endereco,
                    permissao_id = :permissao_id
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':telefone' => $telefone,
            ':sexo' => $sexo,
            ':data_nascimento' => $data_nascimento,
            ':cidade' => $cidade,
            ':estado' => $estado,
            ':endereco' => $endereco,
            ':permissao_id' => $permissao_id,
            ':id' => $id
        ]);
    }

    header('Location: sistema.php?page=usuarios');
?>