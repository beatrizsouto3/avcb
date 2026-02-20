<?php include 'configuracoes/variaveis.php'; // Inclui o arquivo e as variáveis?>
<?php
$sucesso = false;
$erroEmail = false;

?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>REGISTRO | <?php echo $empresa; ?> - AVCB</title>
    <style>
        body {
            background-color: #000;
            background-image: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #000 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .container-box {
            background-color: #111;
            padding: 50px;
            border-radius: 0px;
            border: 1px solid #333;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            text-align: center;
        }

        .logo-area {
            margin-bottom: 30px;
        }

        .logo-area i {
            font-size: 3rem;
            color: #fff;
        }

        h2 {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        p.lead {
            color: #888;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        .contato-destaque {
            background: #fff;
            color: #000;
            padding: 20px;
            margin-bottom: 30px;
            transition: 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            border: 2px solid #fff;
        }

        .contato-destaque:hover {
            background: #000;
            color: #fff;
        }

        .contato-destaque i {
            font-size: 1.5rem;
        }

        .contato-destaque span {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .btn-voltar {
            color: #666;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-voltar:hover {
            color: #fff;
        }

        .divider {
            height: 1px;
            background: #333;
            margin: 30px 0;
        }
    </style>
</head>
<body>

<div class="container-box">
    <div class="logo-area">
        <i class="bi bi-shield-lock"></i>
    </div>
    
    <h2>SOLICITAR ACESSO</h2>
    <p class="lead">O registro de novos clientes é realizado exclusivamente pela nossa equipa administrativa.</p>
    
    <div class="divider"></div>

    <p class="small text-uppercase opacity-50 mb-3">Fale connosco via WhatsApp</p>
    
    <a href="https://wa.me/5584981914191" target="_blank" class="contato-destaque">
        <i class="bi bi-whatsapp"></i>
        <span>(84) 98191-4191</span>
    </a>

    <p class="text-muted small px-4">
        Ao entrar em contacto, tenha em mãos os seus dados de identificação (CPF/CNPJ) para agilizar o processo.
    </p>

    <div class="divider"></div>

    <a href="login.php" class="btn-voltar">
        <i class="bi bi-arrow-left me-2"></i> Voltar para o Login
    </a>
</div>

</body>
</html>