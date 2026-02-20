<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM processos WHERE id=:id";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute([':id' => $id]);
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
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Editar Processo | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; transition: all 0.3s ease; }
        .container { max-width: 800px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 1rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .btn-update { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .btn-voltar { color: var(--bs-emphasis-color); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="sistema.php?page=processos" class="btn-voltar"><i class="bi bi-arrow-left"></i> VOLTAR</a>
                <h2 class="fw-bold mt-2">EDITAR PROCESSO #<?php echo $id; ?></h2>
            </div>
            <button class="btn btn-outline-secondary btn-sm" id="btn-tema">
                <i class="bi bi-moon-stars-fill"></i> TEMA
            </button>
        </div>

        <form action="saveEditProcesso.php" method="POST">
            <fieldset>
                <legend>Dados do Processo</legend>
                <div class="mb-3">
                    <label class="form-label">Cliente Responsável</label>
                    <select name="cliente_id" class="form-select">
                        <?php while($cli = $stmtClientes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cli['id']; ?>" <?php echo ($cli['id'] == $proc['cliente_id']) ? 'selected' : ''; ?>>
                                <?php echo $cli['nome']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" value="<?php echo $proc['numero_processo']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Atual</label>
                        <select name="status" class="form-select">
                            <option <?php echo ($proc['status'] == 'Em Análise') ? 'selected' : ''; ?>>Em Análise</option>
                            <option <?php echo ($proc['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                            <option <?php echo ($proc['status'] == 'Aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                            <option <?php echo ($proc['status'] == 'Reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                            <option <?php echo ($proc['status'] == 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Histórico / Descrição</legend>
                <label class="form-label">Detalhes da Ocorrência</label>
                <textarea name="descricao" class="form-control" rows="5"><?php echo $proc['descricao']; ?></textarea>
            </fieldset>

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" name="update" class="btn-update mb-5">Salvar Alterações</button>
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