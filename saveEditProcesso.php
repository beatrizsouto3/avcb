<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $numero = $_POST['numero_processo'];
        $descricao = $_POST['descricao'];
        $status = $_POST['status'];
        $cliente_id = $_POST['cliente_id'];

        $sql = "UPDATE processos SET numero_processo = :num, descricao = :desc, status = :status, cliente_id = :cli WHERE id = :id";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':num' => $numero, ':desc' => $descricao, ':status' => $status, ':cli' => $cliente_id, ':id' => $id]);
            header('Location: sistema.php?page=processos&msg=atualizado');
        } catch(PDOException $e) { echo "Erro: " . $e->getMessage(); }
    } else {
        header('Location: sistema.php?page=processos');
    }
?>