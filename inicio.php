<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inicio</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .box{
            background-color: rgba(0,0,0,0.6);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }
        .btn-inicio {
            text-decoration: none;
            color: white;
            border: 2px solid limegreen;
            border-radius: 10px;
            padding: 12px;
            display: block;
            margin: 15px 0;
            transition: 0.3s;
            font-size: 1.1rem;
        }
        .btn-inicio:hover{
            background-color: limegreen;
            color: white;
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <h1>Autos de Vistoria do Corpo de Bombeiros - RN</h1>
    <div class="box">
        <a href="login.php" class="btn-inicio">Login</a>
        <a href="cadastro.php" class="btn-inicio">Cadastre-se</a>
    </div>
</body>
</html>