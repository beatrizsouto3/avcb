<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        header('Location: login.php');
        exit;
    }

    $msgAlert = "";

    if(isset($_POST['submit'])){
        $novaSenha = $_POST['novaSenha'];
        $confirmaSenha = $_POST['confirmaSenha'];
        $id = $_SESSION['id_usuario']; 

        if($novaSenha === $confirmaSenha){
            $senhaHash = md5($novaSenha);
            
            $sql = "UPDATE usuarios SET senha = :senha, primeiro_acesso = 'false' WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':senha' => $senhaHash, ':id' => $id]);

            $_SESSION['senha'] = $senhaHash;

            $msgAlert = "
                Swal.fire({
                    icon: 'success',
                    title: 'SENHA ATUALIZADA',
                    text: 'Sua senha foi alterada com sucesso.',
                    background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#121212' : '#fff',
                    color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#fff' : '#000',
                    confirmButtonColor: '#000'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'sistema.php';
                    }
                });
            ";
        } else {
            $msgAlert = "
                Swal.fire({
                    icon: 'error',
                    title: 'SENHAS DIFERENTES',
                    text: 'A confirmação de senha não confere.',
                    background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#121212' : '#fff',
                    color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#fff' : '#000',
                    confirmButtonColor: '#d33'
                });
            ";
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
    <title>Definir Senha | AVCB</title>
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
        .container-box { 
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
            letter-spacing: 1px;
        }
        .btn-custom:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container-box">
        <i class="bi bi-shield-lock display-4 mb-3"></i>
        <h2 class="fw-bold mb-3 text-uppercase">Nova Senha</h2>
        <p class="opacity-75 mb-4">Por segurança, defina uma senha definitiva para o seu acesso.</p>
        
        <form action="definirSenha.php" method="POST">
            <input type="password" name="novaSenha" class="form-control" placeholder="Nova Senha" required>
            <input type="password" name="confirmaSenha" class="form-control" placeholder="Confirme a Nova Senha" required>
            <button type="submit" name="submit" class="btn-custom">Atualizar Senha</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const temaSalvo = localStorage.getItem('tema') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', temaSalvo);
        
        <?php echo $msgAlert; ?>
    </script>
</body>
</html>