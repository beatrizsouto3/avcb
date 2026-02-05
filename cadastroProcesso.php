<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(isset($_POST['submit'])){
        $numero = $_POST['numero_processo'];
        $descricao = $_POST['descricao'];
        $cliente_id = $_POST['cliente_id'];
        $status = $_POST['status'];

        $sql = "INSERT INTO processos (numero_processo, descricao, status, cliente_id, data_criacao) 
                VALUES (:num, :desc, :status, :cliente, NOW())";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':num' => $numero, ':desc' => $descricao, ':status' => $status, ':cliente' => $cliente_id
            ]);
            header('Location: sistema.php?page=processos&msg=cadastrado');
            exit;
        } catch (PDOException $e) { echo "Erro: " . $e->getMessage(); }
    }

    $sqlClientes = "SELECT id, nome, cpf_cnpj FROM usuarios WHERE permissao_id = 2 ORDER BY nome ASC";
    $stmtClientes = $pdo->query($sqlClientes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Novo Processo</title>
    <style>
        body{ background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35)); min-height: 100vh; padding: 20px; color: white; }
        .container-box { background-color: rgba(0, 0, 0, 0.7); padding: 30px; border-radius: 15px; max-width: 800px; margin: auto; }
        fieldset { border: 1px solid limegreen; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        legend { background-color: limegreen; padding: 5px 15px; border-radius: 5px; color: black; font-weight: bold; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        .form-control, .form-select { margin-bottom: 15px; }
        .btn-custom { background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34)); width: 100%; border: none; padding: 15px; color: white; cursor: pointer; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container-box">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Novo Processo</h2>
            <a href="sistema.php?page=processos" style="color:white; text-decoration:none; border:1px solid white; padding:5px 10px; border-radius:5px;">Voltar</a>
        </div>
        <hr>
        <form action="cadastroProcesso.php" method="POST">
            <fieldset>
                <legend>Dados do Processo</legend>
                <label>Cliente Vinculado *</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">Selecione o cliente...</option>
                    <?php while($cli = $stmtClientes->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $cli['id']; ?>"><?php echo $cli['nome']; ?> (Doc: <?php echo $cli['cpf_cnpj']; ?>)</option>
                    <?php endwhile; ?>
                </select>
                <div class="row">
                    <div class="col-md-6">
                        <label>Número do Processo *</label>
                        <input type="text" name="numero_processo" class="form-control" required placeholder="Ex: 2026.001.AVC">
                    </div>
                    <div class="col-md-6">
                        <label>Status Atual</label>
                        <select name="status" class="form-select">
                            <option>Em Análise</option><option>Pendente</option><option>Aprovado</option><option>Reprovado</option><option>Finalizado</option>
                        </select>
                    </div>
                </div>
                <label>Descrição</label><textarea name="descricao" class="form-control" rows="4"></textarea>
            </fieldset>
            <button type="submit" name="submit" class="btn-custom">Cadastrar Processo</button>
        </form>
    </div>
</body>
</html>