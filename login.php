<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Login | Sistema AVCB</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            transition: all 0.3s ease;
        }

        .tela-login {
            background-color: var(--bs-tertiary-bg);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid var(--bs-border-color);
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        h1 {
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            padding: 12px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-emphasis-color);
            box-shadow: none;
        }

        .inputSubmit {
            background-color: var(--bs-emphasis-color);
            color: var(--bs-body-bg);
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
        }

        .inputSubmit:hover {
            opacity: 0.8;
        }

        .erro-msg {
            color: #ff4d4d;
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .link-footer {
            color: var(--bs-body-color);
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .link-footer:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

    <div class="theme-toggle">
        <button class="btn btn-sm btn-outline-secondary" id="btn-tema">
            <i class="bi bi-moon-stars-fill"></i>
        </button>
    </div>

    <div class="tela-login">
        <h1>LOGIN</h1>
        <form action="testLogin.php" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small opacity-50">E-mail</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3 text-start">
                <label class="form-label small opacity-50">Palavra-passe</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            
            <div class="text-end mb-4">
                <a href="esqueciSenha.php" class="link-footer">Esqueceu-se da senha?</a>
            </div>

            <?php if(isset($_SESSION['nao_autenticado'])): ?>
                <div class="erro-msg">Utilizador ou senha inválidos.</div>
            <?php endif; unset($_SESSION['nao_autenticado']); ?>
            
            <input class="inputSubmit" type="submit" name="submit" value="Entrar">
        </form>
        
        <div class="text-center mt-4">
            <a href="inicio.php" class="link-footer"><i class="bi bi-arrow-left"></i> Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const btnTema = document.getElementById('btn-tema');
        const html = document.documentElement;

        const setTema = (tema) => {
            html.setAttribute('data-bs-theme', tema);
            localStorage.setItem('tema', tema);
            const icon = btnTema.querySelector('i');
            icon.className = tema === 'dark' ? 'bi bi-moon-stars-fill' : 'bi bi-brightness-high-fill';
        };

        const temaSalvo = localStorage.getItem('tema') || 'dark';
        setTema(temaSalvo);

        btnTema.addEventListener('click', () => {
            const novo = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTema(novo);
        });

        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');
        if(msg === 'recuperacao_ok') {
            Swal.fire({
                icon: 'success',
                title: 'E-mail Enviado!',
                text: 'Verifique a sua caixa de entrada.',
                background: temaSalvo === 'dark' ? '#121212' : '#fff',
                color: temaSalvo === 'dark' ? '#fff' : '#000',
                confirmButtonColor: '#000'
            });
        }
    </script>
</body>
</html>