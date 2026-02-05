<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3)){
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

            if($_SESSION['permissao'] == 3 && $user_data['permissao_id'] != 2){
                header('Location: sistema.php?page=usuarios&msg=sem_permissao');
                exit;
            }

            $sqlDelete = "DELETE FROM usuarios WHERE id=$id";
            $stmtDelete = $pdo->prepare($sqlDelete);
            $stmtDelete->execute();
            
            header('Location: sistema.php?page=usuarios&msg=deletado');
        }
        else {
            header('Location: sistema.php?page=usuarios');
        }
    }
    else {
        header('Location: sistema.php?page=usuarios');
    }
?>