<?php include 'configuracoes/variaveis.php'; // Inclui o arquivo e as variáveis?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Início | <?php echo $empresa; ?> - Sistema AVCB</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            transition: background 0.3s, color 0.3s;
        }

        .box {
            background-color: var(--bs-tertiary-bg);
            padding: 50px;
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--bs-border-color);
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .btn-inicio {
            text-decoration: none;
            color: var(--bs-body-color);
            border: 1px solid var(--bs-body-color);
            border-radius: 8px;
            padding: 14px;
            display: block;
            margin: 15px 0;
            transition: 0.3s all ease;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .btn-inicio:hover {
            background-color: var(--bs-emphasis-color);
            color: var(--bs-body-bg);
            border-color: var(--bs-emphasis-color);
        }

        .logo-icon {
            font-size: 4rem;
            color: var(--bs-emphasis-color);
            margin-bottom: 20px;
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .logo-brasas {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="theme-toggle">
        <button class="btn btn-outline-secondary border-0 fs-3" id="btn-tema">
            <i class="bi bi-moon-stars-fill"></i>
        </button>
    </div>

    <!--<div class="box">
        <div class="logo-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
    -->
    <div class="box">
        <div class="logo-icon">
            <img src="img/logo_brasas.png" alt="Logo JR Fire" class="logo-brasas">
        </div>
        <h1>SISTEMA AVCB</h1>
        
        <div class="d-grid gap-2">
            <a href="login.php" class="btn-inicio">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </a>
            <a href="cadastro.php" class="btn-inicio">
                <i class="bi bi-person-plus me-2"></i> Registrar-se
            </a>
        </div>
        
        <p class="mt-4 opacity-50 small">© 2026 SISTEMA AVCB | Todos os direitos reservados.</p>
    </div>

    <script>
        const btnTema = document.getElementById('btn-tema');
        const html = document.documentElement;

        const setTema = (tema) => {
            html.setAttribute('data-bs-theme', tema);
            localStorage.setItem('tema', tema);
            const icon = btnTema.querySelector('i');
            if (tema === 'dark') {
                icon.className = 'bi bi-moon-stars-fill';
            } else {
                icon.className = 'bi bi-brightness-high-fill';
            }
        };

        const temaSalvo = localStorage.getItem('tema') || 'dark';
        setTema(temaSalvo);

        btnTema.addEventListener('click', () => {
            const novoTema = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTema(novoTema);
        });
    </script>

</body>
</html>