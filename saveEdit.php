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
        $senhaPost = $_POST['senha'];
        $senha = !empty($senhaPost) ? md5($senhaPost) : '';

        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['data_nascimento'];
        $permissao_id = $_POST['permissao_id']; 
        
        $sql = "UPDATE usuarios 
                SET nome = :nome, 
                    email = :email, 
                    senha = CASE WHEN :senha = '' THEN senha ELSE :senha END, 
                    telefone = :telefone, 
                    data_nascimento = :data_nascimento, 
                    permissao_id = :permissao_id
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':telefone' => $telefone,
            ':data_nascimento' => $data_nascimento,
            ':permissao_id' => $permissao_id,
            ':id' => $id
        ]);
    }

    header('Location: sistema.php?page=usuarios&msg=atualizado');
?>