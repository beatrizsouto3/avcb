<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <title>Tela de login</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;
        }
        .tela-login{
            background-color: rgba(0, 0, 0, 0.6); padding: 40px; border-radius: 15px; color: white; width: 90%; max-width: 450px;
        }
        input{ padding: 12px; border: none; outline: none; font-size: 15px; width: 100%; border-radius: 5px; margin-bottom: 15px; }
        .inputSubmit{ background-color: limegreen; border: none; padding: 15px; width: 100%; border-radius: 10px; color: white; font-size: 16px; cursor: pointer; font-weight: bold; }
        .inputSubmit:hover{ background-color: #32CD32; }
        .btn-voltar{ text-decoration: none; color: white; border: 1px solid limegreen; border-radius: 5px; padding: 5px 10px; background-color: transparent; font-size: 0.9rem; }
        .btn-voltar:hover{ background-color: limegreen; }
        .erro-msg { color: #ffcccc; background-color: rgba(255, 0, 0, 0.2); padding: 10px; border: 1px solid red; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 14px; }
        h1 { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="tela-login">
        <a href="inicio.php" class="btn-voltar">⭠ Voltar</a>
        <br><br>
        <h1>Login</h1>
        <form action="testLogin.php" method="POST">
            <input type="text" name="email" placeholder="Email" class="form-control" required>
            <input type="password" name="senha" placeholder="Senha" class="form-control" required>
            
            <div style="text-align: right; margin-bottom: 15px;">
                <a href="esqueciSenha.php" style="color: limegreen; text-decoration: none; font-size: 0.9rem;">Esqueci minha senha</a>
            </div>

            <?php if(isset($_SESSION['nao_autenticado'])): ?>
            <div class="erro-msg">Usuário ou senha inválidos.</div>
            <?php endif; unset($_SESSION['nao_autenticado']); ?>
            
            <br>
            <input class="inputSubmit" type="submit" name="submit" value="Entrar">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');

        if(msg === 'recuperacao_ok') {
            Swal.fire({
                icon: 'success',
                title: 'E-mail Enviado!',
                text: 'Uma senha temporária foi enviada para o seu e-mail. Verifique sua caixa de entrada (e spam).',
                background: '#1f1f1f',
                color: '#fff',
                confirmButtonColor: 'limegreen'
            });
        }
    </script>
</body>
</html>