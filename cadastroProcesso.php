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
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Processo | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; transition: all 0.3s ease; }
        .container { max-width: 800px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 1rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .btn-custom { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .btn-voltar { color: var(--bs-emphasis-color); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="sistema.php?page=processos" class="btn-voltar"><i class="bi bi-arrow-left"></i> VOLTAR</a>
                <h2 class="fw-bold mt-2">NOVO PROCESSO</h2>
            </div>
            <button class="btn btn-outline-secondary btn-sm" id="btn-tema">
                <i class="bi bi-moon-stars-fill"></i> TEMA
            </button>
        </div>

        <form action="cadastroProcesso.php" method="POST">
            <fieldset>
                <legend>Vínculo e Identificação</legend>
                <div class="mb-3">
                    <label class="form-label">Cliente Vinculado *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Selecione o cliente...</option>
                        <?php while($cli = $stmtClientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cli['id']; ?>"><?php echo $cli['nome']; ?> (Doc: <?php echo $cli['cpf_cnpj']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número do Processo *</label>
                        <input type="text" name="numero_processo" class="form-control" required placeholder="Ex: 2026.001.AVC">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Inicial</label>
                        <select name="status" class="form-select">
                            <option>Em Análise</option><option>Pendente</option><option>Aprovado</option><option>Reprovado</option><option>Finalizado</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Informações Adicionais</legend>
                <label class="form-label">Descrição do Processo</label>
                <textarea name="descricao" class="form-control" rows="4" placeholder="Detalhes técnicos ou observações..."></textarea>
            </fieldset>

            <button type="submit" name="submit" class="btn-custom mb-5">Finalizar Cadastro</button>
        </form>
    </div>

    <script>
        const btnTema = document.getElementById('btn-tema');
        const html = document.documentElement;
        const setTema = (tema) => {
            html.setAttribute('data-bs-theme', tema);
            localStorage.setItem('tema', tema);
            btnTema.innerHTML = tema === 'dark' ? '<i class="bi bi-moon-stars-fill"></i> TEMA' : '<i class="bi bi-brightness-high-fill"></i> TEMA';
        };
        setTema(localStorage.getItem('tema') || 'dark');
        btnTema.addEventListener('click', () => {
            const novo = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTema(novo);
        });
    </script>
</body>
</html>