<?php
    session_start();

    if(!isset($_SESSION['email']) || $_SESSION['permissao'] != 1){
        header('Location: sistema.php?page=documentos&msg=sem_permissao');
        exit;
    }

    if(!empty($_GET['id']))
    {
        include_once('config.php');

        $id = $_GET['id'];

        $sqlSelect = "SELECT caminho_arquivo FROM documentos WHERE id=$id";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            $arquivoParaDeletar = "uploads/" . $dados['caminho_arquivo'];

            if(file_exists($arquivoParaDeletar)){
                unlink($arquivoParaDeletar);
            }

            $sqlDelete = "DELETE FROM documentos WHERE id=:id";
            $stmtDelete = $pdo->prepare($sqlDelete);
            $stmtDelete->execute([':id' => $id]);
        }
    }

    header('Location: sistema.php?page=documentos&msg=doc_deletado');
?>