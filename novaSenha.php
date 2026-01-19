<?php
    session_start();
    include_once('config.php');

    if(!isset($_SESSION['email'])){
        header('Location: login.php');
        exit;
    }

    if(isset($_POST['submit'])){
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        $id = $_SESSION['id_usuario'];

        if($nova_senha === $confirmar_senha){
            $senha_md5 = md5($nova_senha);

            $sql = "UPDATE usuarios SET senha = :senha, primeiro_acesso = 'false' WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':senha' => $senha_md5,
                ':id' => $id
            ]);

            $_SESSION['senha'] = $senha_md5;
            
            echo "<script>alert('Senha alterada com sucesso!'); window.location='sistema.php';</script>";
        } else {
            echo "<script>alert('As senhas não conferem!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box{
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 15px;
            color: white;
            text-align: center;
            width: 300px;
        }
        input{
            padding: 15px;
            width: 90%;
            border-radius: 5px;
            border: none;
            outline: none;
            margin-bottom: 20px;
        }
        button{
            background-color: dodgerblue;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover{
            background-color: deepskyblue;
        }
        h2{ margin-bottom: 20px; }
        p { font-size: 14px; color: #ccc; margin-bottom: 30px;}
    </style>
</head>
<body>
    <div class="box">
        <h2>Bem-vindo(a)!</h2>
        <p>Como este é seu primeiro acesso com a senha temporária, você precisa definir uma senha segura e pessoal.</p>
        
        <form action="novaSenha.php" method="POST">
            <input type="password" name="nova_senha" placeholder="Nova Senha" required minlength="4">
            <input type="password" name="confirmar_senha" placeholder="Confirme a Nova Senha" required minlength="4">
            
            <button type="submit" name="submit">Salvar Senha e Entrar</button>
        </form>
    </div>
</body>
</html>