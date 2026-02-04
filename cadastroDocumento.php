<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        header('Location: login.php');
        exit;
    }

    $codigo_aleatorio = "DOC-" . strtoupper(substr(md5(uniqid()), 0, 4));
    
    date_default_timezone_set('America/Sao_Paulo');
    $data_atual = date('d/m/Y');
    $hora_atual = date('H:i');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Novo Documento</title>
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
            max-width: 600px;
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
        .inputBox{ position: relative; margin-bottom: 20px; }
        .inputUser{
            background: rgba(255,255,255,0.1);
            border: 1px solid limegreen;
            border-radius: 5px;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            padding: 10px;
        }
        label{ margin-bottom: 5px; display: block; font-weight: bold; }
        
        select.inputUser option {
            background-color: #333;
            color: white;
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
    </style>
</head>
<body>
    <div class="box">
        <a href="sistema.php?page=documentos" class="btn-voltar">⭠ Voltar</a>
        <br><br>
        <form action="salvarDocumento.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend><b>Registrar Documento</b></legend>

                <div class="row">
                    <div class="col-md-6">
                        <label>ID do Documento:</label>
                        <input type="text" name="codigo" class="inputUser" value="<?php echo $codigo_aleatorio; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Data/Hora Registro:</label>
                        <input type="text" class="inputUser" value="<?php echo $data_atual . ' - ' . $hora_atual; ?>" readonly>
                    </div>
                </div>
                <br>

                <label>Tipo de Documento:</label>
                <select name="tipo_documento" class="inputUser" required>
                    <option value="" disabled selected>Selecione um tipo...</option>
                    <option value="Auto de Vistoria">Auto de Vistoria</option>
                    <option value="Declaração">Declaração</option>
                    <option value="Parecer">Parecer</option>
                    <option value="Laudo">Laudo</option>
                </select>
                <br><br>

                <label>Anexar Arquivo (PDF/Imagem):</label>
                <input type="file" name="arquivo" class="inputUser" required>
                <br>

                <input type="submit" name="submit" class="btn-custom" value="Salvar Documento">
            </fieldset>
        </form>
    </div>
</body>
</html>