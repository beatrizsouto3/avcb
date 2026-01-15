<?php
    session_start();
    // print_r($_SESSION);

    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        header('Location: login.php');
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
    }
    $logado = $_SESSION['email']

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>SISTEMA | AVCB</title>
    <style>
        body{
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema | AVCB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="d-flex">
            <a href="sair.php" class="btn btn-danger me-5">Sair</a>
        </div>
    </nav>
    <br>
  <?php
    echo "<h1>Bem vindo <u>$logado</u></h1>";
  ?> 
</body>
</html>