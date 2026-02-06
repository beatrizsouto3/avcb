<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <title>Recuperar Senha</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;
        }
        .container-box{
            background-color: rgba(0, 0, 0, 0.7); padding: 40px; border-radius: 15px; color: white; width: 90%; max-width: 450px; text-align: center;
        }
        input{ padding: 12px; border: none; outline: none; font-size: 15px; width: 100%; border-radius: 5px; margin-bottom: 15px; }
        .btn-custom{ background-color: limegreen; border: none; padding: 12px; width: 100%; border-radius: 5px; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-custom:hover{ background-color: #32CD32; }
        .link-voltar{ color: white; text-decoration: none; display: block; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container-box">
        <h2>Recuperar Senha</h2>
        <p class="mb-4">Digite seu e-mail cadastrado para receber uma senha temporária.</p>
        
        <form action="processaRecuperacao.php" method="POST">
            <input type="email" name="email" placeholder="Seu e-mail" required class="form-control">
            <button type="submit" name="submit" class="btn-custom">Enviar Nova Senha</button>
        </form>
        
        <a href="login.php" class="link-voltar">Voltar para o Login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');

        if(msg === 'email_nao_encontrado') {
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'E-mail não encontrado no sistema!',
                background: '#1f1f1f',
                color: '#fff',
                confirmButtonColor: 'limegreen'
            });
        } else if(msg === 'erro_envio') {
            Swal.fire({
                icon: 'error',
                title: 'Erro técnico',
                text: 'Não foi possível enviar o e-mail. Tente novamente mais tarde.',
                background: '#1f1f1f',
                color: '#fff',
                confirmButtonColor: 'limegreen'
            });
        }
    </script>
</body>
</html>