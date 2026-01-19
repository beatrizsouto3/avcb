<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de login</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
        }
        .tela-login{
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            padding: 80px;
            border-radius: 15px;
            color: white;
        }
        input{
            padding: 15px;
            border: none;
            outline: none;
            font-size: 15px;
        }
        .inputSubmit{
            background-color: dodgerblue;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            color: white;
            font-size: 15px;
        }
        .inputSubmit:hover{
            background-color: deepskyblue;
            cursor: pointer;
        }
        a{
            text-decoration: none;
            color: white;
            border: 2px solid dodgerblue;
            border-radius: 5px;
            padding: 5px;
            background-color: dodgerblue;
        }
        a:hover{
            background-color: deepskyblue;
        }
        .erro-msg {
            color: #ffcccc;
            background-color: rgba(255, 0, 0, 0.2);
            padding: 3px;
            border: 1px solid red;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="tela-login">
        <a href="inicio.php">⭠ Voltar</a>
        <br><br>
        <h1>Login</h1>
        <form action="testLogin.php" method="POST">
            <input type="text" name="email" placeholder="Email">
            <br><br>
            <input type="password" name="senha" placeholder="Senha">
            <br><br>

            <?php
            if(isset($_SESSION['nao_autenticado'])):
            ?>
            <div class="erro-msg">
                <p>Usuário ou senha inválidos.</p>
            </div>
            <?php
            endif;
            unset($_SESSION['nao_autenticado']);
            ?>
            <input class="inputSubmit" type="submit" name="submit" value="Enviar">
        </form>
    </div>
</body>
</html>