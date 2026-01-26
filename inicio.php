<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            text-align: center;
            color: white;
        }
        .box{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            background-color: rgba(0,0,0,0.6);
            padding: 30px;
            border-radius: 15px;
        }
        a{
            text-decoration: none;
            color: white;
            border: 3px solid limegreen;
            border-radius: 10px;
            padding: 10px;
        }
        a:hover{
            background-color: limegreen;
        }
    </style>
</head>
<body>
    <h1>Autos de Vistoria do Corpo de Bombeiros - RN</h1>
    <div class="box">
        <a href="login.php">Login</a>
        <a href="cadastro.php">Cadastre-se</a>
    </div>
</body>
</html>