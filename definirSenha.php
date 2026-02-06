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
                    title: 'Senha Atualizada!',
                    text: 'Sua senha foi alterada com sucesso.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: 'limegreen'
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
                    title: 'Senhas Diferentes',
                    text: 'A confirmação de senha não confere.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: '#d33'
                });
            ";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <title>Definir Nova Senha</title>
    <style>
        body{ background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35)); min-height: 100vh; display: flex; justify-content: center; align-items: center; color: white;}
        .container-box{ background-color: rgba(0, 0, 0, 0.8); padding: 40px; border-radius: 15px; width: 100%; max-width: 500px; border: 1px solid limegreen; text-align: center; }
        input { margin-bottom: 15px; }
        .btn-custom { background-color: limegreen; border: none; width: 100%; padding: 10px; color: white; font-weight: bold; border-radius: 5px; }
        .btn-custom:hover { background-color: #32CD32; }
    </style>
</head>
<body>
    <div class="container-box">
        <h2 class="mb-3">Definir Nova Senha</h2>
        <p class="text-warning mb-4">Detectamos que este é seu primeiro acesso ou você solicitou recuperação. Por segurança, defina uma nova senha.</p>
        
        <form action="definirSenha.php" method="POST">
            <input type="password" name="novaSenha" class="form-control" placeholder="Nova Senha" required>
            <input type="password" name="confirmaSenha" class="form-control" placeholder="Confirme a Nova Senha" required>
            <button type="submit" name="submit" class="btn-custom">Salvar Nova Senha</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php echo $msgAlert; ?>
    </script>
</body>
</html>