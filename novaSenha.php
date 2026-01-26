<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['id_usuario']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
        exit;
    }

    $sucesso = false;
    $erro = false;

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
            
            $sucesso = true;
        } else {
            $erro = true;
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
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }
        .box{
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            min-width: 350px;
            max-width: 500px;
        }
        input{
            padding: 15px;
            width: 90%;
            border-radius: 5px;
            border: none;
            outline: none;
            margin-bottom: 20px;
            font-size: 15px;
        }
        .btn-custom {
            background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            box-sizing: border-box;
        }
        .btn-custom:hover{
            background-image: linear-gradient(to right, rgb(34, 139, 34), rgb(0, 100, 0));
        }
        h2{ margin-bottom: 20px; }
        p { font-size: 14px; color: #ccc; margin-bottom: 30px;}

        .sucesso-titulo { color: limegreen; font-size: 1.5rem; margin-bottom: 15px; }
        .sucesso-texto { font-size: 1.1rem; margin-bottom: 30px; color: white; }
        
        .msg-erro {
            color: #ffcccc;
            background-color: rgba(255, 0, 0, 0.2);
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    
    <?php if($sucesso == true): ?>
        
        <div class="box">
            <h2 class="sucesso-titulo">Tudo pronto!</h2>
            <p class="sucesso-texto">Sua senha definitiva foi criada com sucesso.</p>
            
            <a href="sistema.php" class="btn-custom">Entrar no Sistema</a>
        </div>

    <?php else: ?>

        <div class="box">
            <h2>Criar Senha</h2>
            <p>Como este é seu primeiro acesso, defina sua senha pessoal.</p>
            
            <?php if($erro == true): ?>
                <div class="msg-erro">
                    As senhas digitadas não coincidem. Tente novamente.
                </div>
            <?php endif; ?>

            <form action="novaSenha.php" method="POST">
                <input type="password" name="nova_senha" placeholder="Nova Senha" required minlength="4">
                <input type="password" name="confirmar_senha" placeholder="Confirme a Nova Senha" required minlength="4">
                
                <button type="submit" name="submit" class="btn-custom">Salvar e Entrar</button>
            </form>
        </div>

    <?php endif; ?>

</body>
</html>