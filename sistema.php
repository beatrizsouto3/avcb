<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
        exit;
    }
    
    $logado = $_SESSION['email'];
    $perm = $_SESSION['permissao'];
    $id_user_logado = $_SESSION['id_usuario'];

    $sqlCheck = "SELECT primeiro_acesso FROM usuarios WHERE id = $id_user_logado";
    $stmtCheck = $pdo->query($sqlCheck);
    if($stmtCheck && $stmtCheck->rowCount() > 0){
        $statusCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if($statusCheck['primeiro_acesso'] == 'true' || $statusCheck['primeiro_acesso'] == 1){
            header('Location: definirSenha.php');
            exit;
        }
    }

    $pagina_atual = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>SISTEMA | AVCB</title>
    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: var(--bs-body-bg); min-height: 100vh; transition: background 0.3s; }
        
        .sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            position: fixed; 
            background: var(--bs-tertiary-bg); 
            border-right: 1px solid var(--bs-border-color);
            padding: 20px;
            z-index: 100;
        }
        .nav-link { 
            color: var(--bs-secondary-color); 
            font-weight: 500; 
            padding: 12px 15px; 
            border-radius: 8px; 
            margin-bottom: 5px; 
            transition: 0.2s;
        }
        .nav-link:hover, .nav-link.active { 
            background: var(--bs-emphasis-color); 
            color: var(--bs-body-bg); 
        }
        
        .main-content { margin-left: var(--sidebar-width); padding: 40px; min-height: 100vh; display: flex; flex-direction: column; }
        .dashboard-welcome { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        
        .table-container { 
            background: var(--bs-tertiary-bg); 
            border-radius: 12px; 
            border: 1px solid var(--bs-border-color); 
            overflow: hidden; 
        }
        .table thead th { 
            background-color: var(--bs-emphasis-color) !important; 
            color: var(--bs-body-bg) !important; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 1px; 
            padding: 15px;
            border: none;
        }
        .section-header { border-bottom: 1px solid var(--bs-border-color); padding-bottom: 20px; margin-bottom: 30px; }
        
        .btn-edit-bw {
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }
        .btn-edit-bw:hover {
            background-color: var(--bs-emphasis-color);
            color: var(--bs-body-bg);
        }

        #btn-tema { cursor: pointer; }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="mb-5 px-2">
            <h5 class="fw-bold m-0 text-uppercase">AVCB ♢<span class="fw-light"> SISTEMA</span></h5>
            <small class="opacity-50">⏺︎ Usuário: <?php echo explode('@', $logado)[0]; ?></small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="sistema.php?page=home" class="nav-link <?php echo ($pagina_atual == 'home') ? 'active' : ''; ?>">
                    <i class="bi bi-grid-1x2 me-2"></i> Dashboard
                </a>
            </li>
            
            <?php if($perm == 1 || $perm == 3): ?>
                <li class="nav-item">
                    <a href="sistema.php?page=usuarios" class="nav-link <?php echo ($pagina_atual == 'usuarios') ? 'active' : ''; ?>">
                        <i class="bi bi-people me-2"></i> Usuários
                    </a>
                </li>
            <?php endif; ?>

            <?php if($perm == 3): ?>
                <li class="nav-item">
                    <a href="sistema.php?page=processos" class="nav-link <?php echo ($pagina_atual == 'processos') ? 'active' : ''; ?>">
                        <i class="bi bi-file-earmark-text me-2"></i> Processos
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="sistema.php?page=documentos" class="nav-link <?php echo ($pagina_atual == 'documentos') ? 'active' : ''; ?>">
                    <i class="bi bi-folder me-2"></i> Documentos
                </a>
            </li>
        </ul>

        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <button class="btn btn-outline-secondary w-100 mb-2" id="btn-tema"><i class="bi bi-moon-stars-fill"></i> TEMA</button>
            <a href="sair.php" class="btn btn-danger w-100 fw-bold">SAIR</a>
        </div>
    </nav>

    <main class="main-content">
        
        <?php if($pagina_atual == 'home'): ?>
            <div class="dashboard-welcome">
                <i class="bi bi-shield-check display-1 mb-3"></i>
                <h1 class="display-3 fw-bold text-uppercase">Bem-vindo</h1>
                <p class="lead opacity-75">Sistema de Gestão AVCB em operação.</p>
                <div class="mt-4 p-3 border rounded-3 bg-body-tertiary">
                    <small class="text-uppercase fw-bold opacity-50 d-block">Nível de Acesso</small>
                    <span class="fs-5 fw-bold"><?php echo ($perm == 1 ? 'ADMINISTRADOR' : ($perm == 3 ? 'GESTOR' : 'CLIENTE')); ?></span>
                </div>
            </div>

        <?php elseif($pagina_atual == 'usuarios' && ($perm == 1 || $perm == 3)): ?>
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="fw-bold m-0 text-uppercase">Listagem de Usuários</h2>
                <a href="cadastroInterno.php" class="btn btn-dark fw-bold border">+ NOVO USUÁRIO</a>
            </div>
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th><th>Nome</th><th>E-mail</th><th>Perfil</th><th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlU = ($perm == 1) ? "SELECT * FROM usuarios ORDER BY id DESC" : "SELECT * FROM usuarios WHERE permissao_id = 2 ORDER BY id DESC";
                            $resU = $pdo->query($sqlU);
                            while($u = $resU->fetch(PDO::FETCH_ASSOC)) {
                                $nivel = ($u['permissao_id'] == 1 ? 'Admin' : ($u['permissao_id'] == 3 ? 'Gestor' : 'Cliente'));
                                echo "<tr>
                                    <td>{$u['id']}</td>
                                    <td class='fw-bold'>{$u['nome']}</td>
                                    <td>{$u['email']}</td>
                                    <td><span class='badge border text-secondary'>$nivel</span></td>
                                    <td class='text-center'>
                                        <a href='edit.php?id={$u['id']}' class='btn btn-sm btn-edit-bw'><i class='bi bi-pencil-square'></i></a>
                                        <button onclick='confirmarDel({$u['id']}, \"usuarios\")' class='btn btn-sm btn-outline-danger'><i class='bi bi-trash'></i></button>
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php elseif($pagina_atual == 'processos' && $perm == 3): ?>
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="fw-bold m-0 text-uppercase">Processos Ativos</h2>
                <a href="cadastroProcesso.php" class="btn btn-dark fw-bold border">+ NOVO PROCESSO</a>
            </div>
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nº Processo</th><th>Status</th><th>Data</th><th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $resP = $pdo->query("SELECT * FROM processos ORDER BY id DESC");
                            while($p = $resP->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                    <td class='fw-bold'>{$p['numero_processo']}</td>
                                    <td><span class='badge bg-dark border'>{$p['status']}</span></td>
                                    <td>".date('d/m/Y', strtotime($p['data_criacao']))."</td>
                                    <td class='text-center'>
                                        <a href='editProcesso.php?id={$p['id']}' class='btn btn-sm btn-edit-bw'><i class='bi bi-pencil-square'></i></a>
                                        <button onclick='confirmarDel({$p['id']}, \"processos\")' class='btn btn-sm btn-outline-danger'><i class='bi bi-trash'></i></button>
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php elseif($pagina_atual == 'documentos'): ?>
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="fw-bold m-0 text-uppercase">Meus Documentos</h2>
                <a href="cadastroDocumento.php" class="btn btn-dark fw-bold border">+ NOVO DOCUMENTO</a>
            </div>
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Cód</th><th>Tipo</th><th>Arquivo</th><th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlD = ($perm == 1) ? "SELECT * FROM documentos ORDER BY id DESC" : "SELECT * FROM documentos WHERE usuario_id = $id_user_logado ORDER BY id DESC";
                            $resD = $pdo->query($sqlD);
                            
                            if($resD->rowCount() == 0) echo "<tr><td colspan='4' class='text-center py-4 opacity-50'>Nenhum documento encontrado.</td></tr>";
                            
                            while($d = $resD->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                    <td class='small fw-bold text-uppercase'>{$d['codigo_identificador']}</td>
                                    <td>{$d['tipo_documento']}</td>
                                    <td class='text-truncate' style='max-width:200px'>{$d['caminho_arquivo']}</td>
                                    <td class='text-center'>
                                        <a href='uploads/{$d['caminho_arquivo']}' target='_blank' class='btn btn-sm btn-edit-bw'><i class='bi bi-eye'></i></a>
                                        <button onclick='confirmarDel({$d['id']}, \"documentos\")' class='btn btn-sm btn-outline-danger'><i class='bi bi-trash'></i></button>
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        
        const msg = urlParams.get('msg');
        if (msg) {
            let texto = '';
            if (msg === 'cadastrado') texto = 'Registro realizado com sucesso!';
            if (msg === 'editado') texto = 'Alterações salvas com sucesso!';
            if (msg === 'doc_deletado') texto = 'Documento removido do sistema.';

            if(texto !== '') {
                Swal.fire({
                    title: 'Sucesso!',
                    text: texto,
                    icon: 'success',
                    confirmButtonColor: '#000',
                    background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1f1f1f' : '#fff',
                    color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#fff' : '#000'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname + "?page=" + (urlParams.get('page') || 'home'));
                });
            }
        }

        function confirmarDel(id, tipo) {
            let url = (tipo === 'usuarios') ? 'delete.php' : (tipo === 'processos' ? 'deleteProcesso.php' : 'deleteDoc.php');
            
            Swal.fire({
                title: 'Confirmar exclusão?',
                text: "Esta ação não poderá ser desfeita.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar',
                background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1f1f1f' : '#fff',
                color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#fff' : '#000'
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = url + '?id=' + id; 
                }
            });
        }

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