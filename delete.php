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

        $sqlSelect = "SELECT * FROM usuarios WHERE id=:id";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute([':id' => $id]);

        if($stmt->rowCount() > 0)
        {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if($_SESSION['permissao'] == 3 && $user_data['permissao_id'] != 2){
                header('Location: sistema.php?page=usuarios&msg=sem_permissao');
                exit;
            }

            try {
                $pdo->beginTransaction();

                $sqlDelProcessos = "DELETE FROM processos WHERE cliente_id = :id";
                $stmtDelProc = $pdo->prepare($sqlDelProcessos);
                $stmtDelProc->execute([':id' => $id]);

                $sqlDelDocs = "DELETE FROM documentos WHERE usuario_id = :id";
                $stmtDelDocs = $pdo->prepare($sqlDelDocs);
                $stmtDelDocs->execute([':id' => $id]);

                $sqlDelete = "DELETE FROM usuarios WHERE id = :id";
                $stmtDelete = $pdo->prepare($sqlDelete);
                $stmtDelete->execute([':id' => $id]);

                $pdo->commit();
                
                header('Location: sistema.php?page=usuarios&msg=deletado');

            } catch (Exception $e) {
                $pdo->rollBack();
                echo "Erro ao excluir: " . $e->getMessage();
            }
        }
        else {
            header('Location: sistema.php?page=usuarios');
        }
    }
    else {
        header('Location: sistema.php?page=usuarios');
    }
?>