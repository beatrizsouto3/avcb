<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM processos WHERE id=$id";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $proc = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            header('Location: sistema.php?page=processos');
            exit;
        }
    } else {
        header('Location: sistema.php?page=processos');
        exit;
    }

    $sqlClientes = "SELECT id, nome FROM usuarios WHERE permissao_id = 2 ORDER BY nome ASC";
    $stmtClientes = $pdo->query($sqlClientes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Processo</title>
    <style>
        body{ background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35)); min-height: 100vh; padding: 20px; color: white; }
        .container-box { background-color: rgba(0, 0, 0, 0.7); padding: 30px; border-radius: 15px; max-width: 800px; margin: auto; }
        fieldset { border: 1px solid limegreen; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        legend { background-color: limegreen; padding: 5px 15px; border-radius: 5px; color: black; font-weight: bold; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        .form-control, .form-select { border: none; margin-bottom: 15px; }
        .btn-custom { background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34)); width: 100%; border: none; padding: 15px; color: white; cursor: pointer; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container-box">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Editar Processo #<?php echo $id; ?></h2>
            <a href="sistema.php?page=processos" style="color:white; text-decoration:none; border:1px solid white; padding:5px 10px; border-radius:5px;">Voltar</a>
        </div>
        <hr>
        <form action="saveEditProcesso.php" method="POST">
            <fieldset>
                <legend>Detalhes</legend>
                <label>Cliente</label>
                <select name="cliente_id" class="form-select">
                    <?php while($cli = $stmtClientes->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $cli['id']; ?>" <?php echo ($cli['id'] == $proc['cliente_id']) ? 'selected' : ''; ?>>
                            <?php echo $cli['nome']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <div class="row">
                    <div class="col-md-6">
                        <label>Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" value="<?php echo $proc['numero_processo']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option <?php echo ($proc['status'] == 'Em Análise') ? 'selected' : ''; ?>>Em Análise</option>
                            <option <?php echo ($proc['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                            <option <?php echo ($proc['status'] == 'Aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                            <option <?php echo ($proc['status'] == 'Reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                            <option <?php echo ($proc['status'] == 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                        </select>
                    </div>
                </div>
                <label>Descrição</label><textarea name="descricao" class="form-control" rows="5"><?php echo $proc['descricao']; ?></textarea>
            </fieldset>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" name="update" class="btn-custom">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>