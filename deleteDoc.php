<?php
    session_start();
    include_once('config.php');

    if(!isset($_SESSION['email']) || ($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3)){
        header('Location: sistema.php?page=documentos&msg=sem_permissao');
        exit;
    }

    if(!empty($_GET['id'])) {
        $id = $_GET['id'];

        $sqlSelect = "SELECT caminho_arquivo FROM documentos WHERE id = :id";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->execute([':id' => $id]);

        if($stmtSelect->rowCount() > 0) {
            $dados = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            $arquivoPath = "uploads/" . $dados['caminho_arquivo'];

            if(file_exists($arquivoPath)) {
                unlink($arquivoPath);
            }

            $sqlDelete = "DELETE FROM documentos WHERE id = :id";
            $stmtDelete = $pdo->prepare($sqlDelete);
            $stmtDelete->execute([':id' => $id]);

            header('Location: sistema.php?page=documentos&msg=doc_deletado');
            exit;
        }
    }

    header('Location: sistema.php?page=documentos');
    exit;
?>