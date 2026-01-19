<?php
$sucesso = false;

if (isset($_POST['submit'])) {

    include_once('config.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    
    $senhaLimpa = substr(md5(time()), 0, 11);
    $senha = md5($senhaLimpa); 
    
    $sql = "INSERT INTO usuarios (nome, email, senha, telefone, data_nascimento, permissao_id, primeiro_acesso) 
            VALUES (:nome, :email, :senha, :telefone, :data_nascimento, 2, 'true')";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':telefone' => $telefone,
            ':data_nascimento' => $data_nascimento
        ]);
        
        include_once('enviarEmail.php');
        enviarEmailBoasVindas($nome, $email, $senhaLimpa);
        
        $sucesso = true;

    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
        }
        .box{
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 35px;
            border-radius: 15px;
            min-width: 420px; 
            max-width: 600px;
        }
        fieldset{
            border: 3px solid dodgerblue;
            padding: 20px; 
        }
        legend{
            border: 1px solid dodgerblue;
            padding: 10px;
            text-align: center;
            background-color: dodgerblue;
            border-radius: 8px;
        }
        .inputBox{ position: relative; }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput,
        .inputUser:not(:placeholder-shown) ~ .labelInput {
            top: -20px;
            font-size: 12px;
            color: dodgerblue;
        }
        #data_nascimento{
            border: none;
            padding: 8px;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            color: #757575;
            width: 100%;
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .btn-custom {
            background-image: linear-gradient(to right,rgb(0, 92, 197), rgb(90, 20, 220));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            box-sizing: border-box;
            margin-top: 10px;
        }
        .btn-custom:hover{
            background-image: linear-gradient(to right,rgb(0, 80, 172), rgb(80, 19, 195));
        }

        a.voltar{
            text-decoration: none;
            color: white;
            border: 2px solid dodgerblue;
            border-radius: 5px;
            padding: 5px;
            background-color: dodgerblue;
        }
        
        .sucesso-container { text-align: center; }
        .sucesso-titulo { color: deepskyblue; margin-bottom: 20px; }
        .sucesso-texto { font-size: 1.1rem; margin-bottom: 30px; line-height: 1.5; }
    </style>
</head>
<body>

    <?php if($sucesso == true): ?>
        
        <div class="box">
            <div class="sucesso-container">
                <h2 class="sucesso-titulo">Cadastro Realizado!</h2>
                <p class="sucesso-texto">
                    Seu cadastro foi concluído.<br><br>
                    Verifique seu <b>e-mail</b> para pegar a senha de acesso.
                </p>
                <a href="login.php" class="btn-custom">Ir para o Login</a>
            </div>
        </div>

    <?php else: ?>

        <div class="box">
            <a href="inicio.php" class="voltar">⭠ Voltar</a>
            <br><br>
            <form action="cadastro.php" method="POST">
                <fieldset>
                    <legend><b>Cadastro Simplificado</b></legend>
                    <br>
                    
                    <div class="inputBox">
                        <input type="text" name="nome" id="nome" class="inputUser" required placeholder=" ">
                        <label for="nome" class="labelInput">Nome completo</label>
                    </div>
                    <br><br>
                    
                    <div class="inputBox">
                        <input type="email" name="email" id="email" class="inputUser" required placeholder=" ">
                        <label for="email" class="labelInput">Email</label>
                    </div>
                    <br><br>
                    
                    <p style="font-size: 12px; color: yellow;">* A senha será enviada para o seu e-mail.</p>
                    <br>

                    <div class="inputBox">
                        <input type="tel" name="telefone" id="telefone" class="inputUser" required placeholder=" ">
                        <label for="telefone" class="labelInput">Telefone</label>
                    </div>
                    <br><br>

                    <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                    <br>
                    <input type="date" name="data_nascimento" id="data_nascimento" required>
                    <br><br>

                    <input type="submit" name="submit" id="submit" class="btn-custom" value="Enviar">
                </fieldset>
            </form>
        </div>

    <?php endif; ?>

</body>
</html>