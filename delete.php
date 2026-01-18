<?php

    session_start();
    if(!isset($_SESSION['email']) || $_SESSION['permissao'] != 1){
    header('Location: sistema.php');
    exit;
    }

    if(!empty($_GET['id']))
    {
        include_once('config.php');

        $id = $_GET['id'];

        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";

        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $sqlDelete = "DELETE FROM usuarios WHERE id=:id";
            
            $stmtDelete = $pdo->prepare($sqlDelete);
            
            $stmtDelete->execute([':id' => $id]);
        }
    }

    header('Location: sistema.php');

?>