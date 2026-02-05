<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        $sqlDelete = "DELETE FROM processos WHERE id=:id";
        $stmt = $pdo->prepare($sqlDelete);
        
        if($stmt->execute([':id' => $id])){
            header('Location: sistema.php?page=processos&msg=deletado');
        } else { echo "Erro ao deletar."; }
    } else {
        header('Location: sistema.php?page=processos');
    }
?>