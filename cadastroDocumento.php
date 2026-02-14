<?php
    ob_start();
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        header('Location: login.php');
        exit;
    }

    $codigo_aleatorio = "DOC-" . strtoupper(substr(md5(uniqid()), 0, 4));
    
    date_default_timezone_set('America/Sao_Paulo');
    $data_atual = date('d/m/Y');
    $hora_atual = date('H:i');
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Novo Documento | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; transition: all 0.3s ease; }
        .container { max-width: 700px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .btn-custom { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .input-readonly { background-color: var(--bs-secondary-bg) !important; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0 text-uppercase">Registrar Documento</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" id="btn-tema"><i class="bi bi-moon-stars-fill"></i> TEMA</button>
                <a href="sistema.php?page=documentos" class="btn btn-outline-danger btn-sm">FECHAR</a>
            </div>
        </div>

        <form action="salvarDocumento.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Informações do Registro</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">ID do Documento</label>
                        <input type="text" name="codigo" class="form-control input-readonly" value="<?php echo $codigo_aleatorio; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Data/Hora Registro</label>
                        <input type="text" class="form-control input-readonly" value="<?php echo $data_atual . ' - ' . $hora_atual; ?>" readonly>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Arquivo e Tipo</legend>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Tipo de Documento</label>
                        <select name="tipo_documento" class="form-select" required>
                            <option value="" disabled selected>Selecione um tipo...</option>
                            <option value="Auto de Vistoria">Auto de Vistoria</option>
                            <option value="Declaração">Declaração</option>
                            <option value="Parecer">Parecer</option>
                            <option value="Laudo">Laudo</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Anexar Arquivo (PDF ou Imagem)</label>
                        <input type="file" name="arquivo" class="form-control" required>
                    </div>
                </div>
            </fieldset>

            <button type="submit" name="submit" class="btn-custom mb-5 shadow">Salvar Documento</button>
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