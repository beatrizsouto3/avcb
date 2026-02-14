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
            $stmt->execute([':senha' => $senha_md5, ':id' => $id]);
            
            $_SESSION['senha'] = $senha_md5;
            $sucesso = true;
        } else {
            $erro = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Criar Senha | B&W</title>
    <style>
        body { 
            background-color: var(--bs-body-bg); 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            color: var(--bs-body-color);
            transition: all 0.3s ease;
        }
        .box { 
            background-color: var(--bs-tertiary-bg); 
            padding: 50px; 
            border-radius: 12px; 
            width: 100%; 
            max-width: 450px; 
            border: 1px solid var(--bs-border-color); 
            text-align: center; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .form-control {
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 15px;
            padding: 12px;
        }
        .btn-custom { 
            background-color: var(--bs-emphasis-color); 
            border: none; 
            width: 100%; 
            padding: 12px; 
            color: var(--bs-body-bg); 
            font-weight: 800; 
            border-radius: 6px; 
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
        }
        .btn-custom:hover { opacity: 0.8; color: var(--bs-body-bg); }
        .msg-erro {
            color: #ff4444;
            background-color: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <div class="position-fixed top-0 end-0 p-3">
        <button class="btn btn-outline-secondary btn-sm" id="btn-tema">
            <i class="bi bi-moon-stars-fill"></i> TEMA
        </button>
    </div>

    <?php if($sucesso == true): ?>
        <div class="box">
            <i class="bi bi-check-circle display-1 mb-4"></i>
            <h2 class="fw-bold mb-3">TUDO PRONTO!</h2>
            <p class="opacity-75 mb-4">Sua senha definitiva foi criada com sucesso.</p>
            <a href="sistema.php" class="btn-custom">Entrar no Sistema</a>
        </div>
    <?php else: ?>
        <div class="box">
            <i class="bi bi-key-fill display-4 mb-3"></i>
            <h2 class="fw-bold mb-2 text-uppercase">Criar Senha</h2>
            <p class="opacity-75 mb-4">Como este é seu primeiro acesso, defina sua senha pessoal de acesso.</p>
            
            <?php if($erro == true): ?>
                <div class="msg-erro">As senhas digitadas não coincidem. Tente novamente.</div>
            <?php endif; ?>

            <form action="novaSenha.php" method="POST">
                <input type="password" name="nova_senha" placeholder="Nova Senha" class="form-control" required minlength="6">
                <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" class="form-control" required>
                <button type="submit" name="submit" class="btn-custom">Salvar e Continuar</button>
            </form>
        </div>
    <?php endif; ?>

    <script>
        const btnTema = document.getElementById('btn-tema');
        const html = document.documentElement;

        const aplicarTema = (tema) => {
            html.setAttribute('data-bs-theme', tema);
            localStorage.setItem('tema', tema);
            const icone = btnTema.querySelector('i');
            icone.className = tema === 'dark' ? 'bi bi-moon-stars-fill' : 'bi bi-brightness-high-fill';
        };

        const temaSalvo = localStorage.getItem('tema') || 'dark';
        aplicarTema(temaSalvo);

        btnTema.addEventListener('click', () => {
            const novoTema = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            aplicarTema(novoTema);
        });
    </script>
</body>
</html>