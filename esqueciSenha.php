<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <title>Recuperar Senha | AVCB</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
            transition: background 0.3s;
        }
        .container-box {
            background-color: var(--bs-tertiary-bg); 
            padding: 40px; 
            border-radius: 12px; 
            color: var(--bs-body-color); 
            width: 90%; 
            max-width: 450px; 
            text-align: center;
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h2 {
            font-weight: 800;
            letter-spacing: -1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            padding: 12px;
            margin-bottom: 20px;
        }
        .form-control:focus {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-emphasis-color);
            box-shadow: none;
        }
        .btn-custom { 
            background-color: var(--bs-emphasis-color); 
            border: none; 
            padding: 12px; 
            width: 100%; 
            border-radius: 6px; 
            color: var(--bs-body-bg); 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            text-transform: uppercase;
            transition: 0.3s; 
        }
        .btn-custom:hover { 
            opacity: 0.8; 
        }
        .link-voltar { 
            color: var(--bs-secondary-color); 
            text-decoration: none; 
            display: block; 
            margin-top: 20px; 
            font-size: 0.9rem;
        }
        .link-voltar:hover {
            color: var(--bs-emphasis-color);
        }
    </style>
</head>
<body>
    <div class="container-box">
        <i class="bi bi-key display-4 mb-3"></i>
        <h2>Recuperar Senha</h2>
        <p class="mb-4 opacity-75">Introduza o seu e-mail para receber uma senha temporária.</p>
        
        <form action="processaRecuperacao.php" method="POST">
            <input type="email" name="email" placeholder="Seu e-mail cadastrado" required class="form-control">
            <button type="submit" name="submit" class="btn-custom">Enviar Nova Senha</button>
        </form>
        
        <a href="login.php" class="link-voltar">
            <i class="bi bi-arrow-left me-1"></i> Voltar para o Login
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const temaSalvo = localStorage.getItem('tema') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', temaSalvo);

        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');

        const swalConfig = {
            background: temaSalvo === 'dark' ? '#1f1f1f' : '#fff',
            color: temaSalvo === 'dark' ? '#fff' : '#000',
            confirmButtonColor: temaSalvo === 'dark' ? '#fff' : '#000'
        };

        if(msg === 'email_nao_encontrado') {
            Swal.fire({
                ...swalConfig,
                icon: 'error',
                title: 'Não encontrado',
                text: 'Este e-mail não existe no nosso sistema.'
            });
        } else if(msg === 'erro_envio') {
            Swal.fire({
                ...swalConfig,
                icon: 'error',
                title: 'Erro de Envio',
                text: 'Não foi possível enviar o e-mail agora. Tente mais tarde.'
            });
        }
    </script>
</body>
</html>