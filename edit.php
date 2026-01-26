<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id']))
    {
        $id = $_GET['id'];

        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";

        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            $nome = $user_data['nome'];
            $email = $user_data['email'];
            $telefone = $user_data['telefone'];
            $data_nascimento = $user_data['data_nascimento'];
            $permissao_id = $user_data['permissao_id'];
        }
        else
        {
            header('Location: sistema.php?page=usuarios');
        }
    }
    else
    {
        header('Location: sistema.php?page=usuarios');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cadastro</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
        }
        .box{
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 15px;
            min-width: 400px;
        }
        fieldset{
            border: 3px solid limegreen;
            padding: 20px;
        }
        legend{
            border: 1px solid limegreen;
            padding: 10px;
            text-align: center;
            background-color: limegreen;
            border-radius: 8px;
            color: black;
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
        .inputUser:valid ~ .labelInput{
            top: -20px;
            font-size: 12px;
            color: limegreen;
        }
        #data_nascimento{
            border: none;
            padding: 8px;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            width: 100%;
            background: rgba(255,255,255,0.1);
            color: white;
        }
        select {
            padding: 8px;
            border-radius: 10px;
            outline: none;
            width: 100%;
        }
        #submit{
            background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            margin-top: 15px;
        }
        #submit:hover{
            background-image: linear-gradient(to right, rgb(34, 139, 34), rgb(0, 100, 0));
        }
        a{
            text-decoration: none;
            color: white;
            border: 2px solid limegreen;
            border-radius: 5px;
            padding: 5px;
            background-color: limegreen;
            color: black;
        }
    </style>
</head>
<body>
    <div class="box">
        <a href="sistema.php?page=usuarios">⭠ Voltar</a>
        <br><br>
        <form action="saveEdit.php" method="POST">
            <fieldset>
                <legend><b>Editar Usuário</b></legend>
                <br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" value="<?php echo $nome;?>" required>
                    <label for="nome" class="labelInput">Nome completo</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser" value="<?php echo $email;?>" required>
                    <label for="email" class="labelInput">Email</label>
                </div>
                <br><br>
                
                <div class="inputBox">
                    <input type="password" name="senha" id="senha" class="inputUser" placeholder="Deixe em branco para manter a atual">
                    <label for="senha" class="labelInput">Senha</label>
                </div>
                <br><br>
                
                <label for="permissao"><b>Nível de Acesso:</b></label>
                <select name="permissao_id" id="permissao">
                    <option value="2" <?php echo ($permissao_id == 2) ? 'selected' : ''; ?>>Comum</option>
                    <option value="1" <?php echo ($permissao_id == 1) ? 'selected' : ''; ?>>Administrador</option>
                </select>
                <br><br>

                <div class="inputBox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser" value="<?php echo $telefone;?>" required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>
                <br><br>

                <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?php echo $data_nascimento;?>" required>
                <br><br>

                <input type="hidden" name="id" value="<?php echo $id;?>">
                <input type="submit" name="update" id="submit" value="Salvar Alterações">
            </fieldset>
        </form>
    </div>
</body>
</html>