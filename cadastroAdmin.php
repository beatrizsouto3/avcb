<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1)){
        header('Location: sistema.php');
        exit;
    }

    $erroEmail = false;
    $nome = $email = $telefone = $data_nascimento = $permissao_id = "";

    if (isset($_POST['submit'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['data_nascimento'];
        $permissao_id = $_POST['permissao_id'];
        
        $sqlVerifica = "SELECT id FROM usuarios WHERE email = :email";
        $stmtVerifica = $pdo->prepare($sqlVerifica);
        $stmtVerifica->execute([':email' => $email]);

        if($stmtVerifica->rowCount() > 0){
            $erroEmail = true;
        }
        else {
            $senhaLimpa = substr(md5(time()), 0, 11);
            $senha = md5($senhaLimpa); 
            
            $sql = "INSERT INTO usuarios (nome, email, senha, telefone, data_nascimento, permissao_id, primeiro_acesso) 
                    VALUES (:nome, :email, :senha, :telefone, :data_nascimento, :permissao_id, 'true')";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha,
                    ':telefone' => $telefone,
                    ':data_nascimento' => $data_nascimento,
                    ':permissao_id' => $permissao_id
                ]);
                
                include_once('enviarEmail.php');
                enviarEmailBoasVindas($nome, $email, $senhaLimpa);
                
                header('Location: sistema.php?page=usuarios&msg=cadastrado');
                exit;

            } catch (PDOException $e) {
                echo "<script>alert('Erro no sistema: " . $e->getMessage() . "');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastro Admin</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .box{
            color: white;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
        }
        fieldset{
            border: 2px solid limegreen;
            padding: 20px;
            border-radius: 10px;
        }
        legend{
            float: none;
            width: auto;
            border: 1px solid limegreen;
            padding: 5px 15px;
            text-align: center;
            background-color: limegreen;
            border-radius: 8px;
            color: black;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .inputBox{ position: relative; margin-bottom: 30px; }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
            padding: 5px;
        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput,
        .inputUser:not(:placeholder-shown) ~ .labelInput{
            top: -20px;
            font-size: 12px;
            color: limegreen;
        }
        #data_nascimento, select{
            border: none;
            padding: 10px;
            border-radius: 5px;
            outline: none;
            font-size: 15px;
            width: 100%;
            background: rgba(255,255,255,0.9);
            color: black;
            margin-bottom: 10px;
        }
        .btn-custom {
            background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            margin-top: 15px;
            font-weight: bold;
        }
        .btn-voltar{
            text-decoration: none;
            color: white;
            border: 1px solid limegreen;
            border-radius: 5px;
            padding: 5px 10px;
            background-color: transparent;
        }
        .msg-erro {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ffcccc;
            border: 1px solid #ff4444;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="box">
        <a href="sistema.php?page=usuarios" class="btn-voltar">⭠ Voltar</a>
        <br><br>
        <form action="cadastroAdmin.php" method="POST">
            <fieldset>
                <legend><b>Novo Usuário</b></legend>
                
                <?php if($erroEmail == true): ?>
                    <div class="msg-erro">
                        ⚠ E-mail já cadastrado! Insira outro.
                    </div>
                <?php endif; ?>

                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required placeholder=" " value="<?php echo $nome; ?>">
                    <label for="nome" class="labelInput">Nome completo</label>
                </div>
                
                <div class="inputBox">
                    <input type="email" name="email" id="email" class="inputUser" required placeholder=" " value="<?php echo $email; ?>">
                    <label for="email" class="labelInput">Email</label>
                </div>
                
                <p style="font-size: 12px; color: yellow; margin-bottom: 15px;">* A senha temporária será enviada por e-mail.</p>

                <label for="permissao" style="display:block; margin-bottom:5px;"><b>Nível de Acesso:</b></label>
                <select name="permissao_id" id="permissao">
                    <option value="2" <?php echo ($permissao_id == 2) ? 'selected' : ''; ?>>Comum</option>
                    <option value="1" <?php echo ($permissao_id == 1) ? 'selected' : ''; ?>>Administrador</option>
                </select>
                <br>

                <div class="inputBox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser" required placeholder=" " value="<?php echo $telefone; ?>">
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>

                <label for="data_nascimento" style="display:block; margin-bottom:5px;"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" required value="<?php echo $data_nascimento; ?>">

                <input type="submit" name="submit" id="submit" class="btn-custom" value="Cadastrar Usuário">
            </fieldset>
        </form>
    </div>
</body>
</html>