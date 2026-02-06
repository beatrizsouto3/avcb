<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    }
    $logado = $_SESSION['email'];
    $perm = $_SESSION['permissao'];

    if(isset($_SESSION['id_usuario'])){
        $id_verificacao = $_SESSION['id_usuario'];
        $sqlCheck = "SELECT primeiro_acesso FROM usuarios WHERE id = $id_verificacao";
        $stmtCheck = $pdo->query($sqlCheck);
        if($stmtCheck && $stmtCheck->rowCount() > 0){
            $statusCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if($statusCheck['primeiro_acesso'] == 'true'){
                header('Location: definirSenha.php');
                exit;
            }
        }
    }

    $pagina_atual = isset($_GET['page']) ? $_GET['page'] : 'home';

    if($pagina_atual == 'usuarios' && $perm != 1 && $perm != 3){ header('Location: sistema.php'); exit; }
    if($pagina_atual == 'processos' && $perm != 3){ header('Location: sistema.php'); exit; }

    if($pagina_atual == 'usuarios'){
        $where = "";
        if($perm == 3){ $where = " AND permissao_id = 2"; }
        if(!empty($_GET['busca'])) {
            $data = $_GET['busca'];
            $sql = "SELECT * FROM usuarios WHERE (nome ILIKE '%$data%' OR email ILIKE '%$data%') $where ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM usuarios WHERE 1=1 $where ORDER BY id DESC";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    if($pagina_atual == 'processos'){
        $sqlBase = "SELECT p.*, u.nome AS nome_cliente FROM processos p LEFT JOIN usuarios u ON p.cliente_id = u.id";
        if(!empty($_GET['busca'])) {
            $data = $_GET['busca'];
            $sql = "$sqlBase WHERE p.numero_processo ILIKE '%$data%' OR u.nome ILIKE '%$data%' ORDER BY p.id DESC";
        } else {
            $sql = "$sqlBase ORDER BY p.id DESC";
        }
        $stmtProcessos = $pdo->prepare($sql);
        $stmtProcessos->execute();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    
    <title>SISTEMA | AVCB</title>
    <style>
        body { background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35)); color: white; min-height: 100vh; overflow-x: hidden; }
        .navbar-custom { background-color: rgba(0,0,0,0.2); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .sidebar-content { background-color: rgba(0, 0, 0, 0.2); height: 100%; display: flex; flex-direction: column; border-right: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header { background-color: rgba(0, 0, 0, 0.3); padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .user-name { font-weight: bold; font-size: 1.1rem; display: block; }
        .user-email { font-size: 0.85rem; opacity: 0.8; }
        .btn-logout-icon { color: white; float: right; font-size: 1.2rem; cursor: pointer; }
        .nav-link { color: white; padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s; display: flex; align-items: center; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background-color: rgba(0,0,0,0.2); border-left: 4px solid #fff; }
        .nav-link i { margin-right: 15px; font-size: 1.2rem; }
        .offcanvas { background-image: linear-gradient(to bottom, rgb(20, 70, 35), rgb(80, 220, 120)); color: white; max-width: 80%; }
        @media (min-width: 992px) { .sidebar-col { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 0; width: 250px; } .main-content { margin-left: 250px; } .btn-menu-mobile { display: none; } }
        .table-bg { background: rgba(0,0,0,0.3); border-radius: 10px; }
        .box-search { display: flex; gap: 10px; }
        @media (max-width: 768px) { .box-search { flex-direction: column; } .w-25 { width: 100% !important; } }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom d-lg-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile"><span class="navbar-toggler-icon"></span></button>
            <span class="navbar-brand mb-0 h1">SISTEMA AVCB</span>
        </div>
    </nav>

    <div class="d-none d-lg-block sidebar-col">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <a href="sair.php" class="btn-logout-icon" title="Sair"><i class="bi bi-box-arrow-right"></i></a>
                <span class="user-name">Bem-vindo</span>
                <span class="user-email"><?php echo $logado; ?></span>
            </div>
            <div class="d-flex flex-column">
                <a class="nav-link <?php echo ($pagina_atual == 'home') ? 'active' : ''; ?>" href="sistema.php"><i class="bi bi-house-door"></i> Início</a>
                <a class="nav-link <?php echo ($pagina_atual == 'documentos') ? 'active' : ''; ?>" href="sistema.php?page=documentos"><i class="bi bi-file-earmark-text"></i> Documentos</a>
                
                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link <?php echo ($pagina_atual == 'usuarios') ? 'active' : ''; ?>" href="sistema.php?page=usuarios"><i class="bi bi-people"></i> Usuários</a>
                <?php endif; ?>

                <?php if($perm == 3): ?>
                <a class="nav-link <?php echo ($pagina_atual == 'processos') ? 'active' : ''; ?>" href="sistema.php?page=processos"><i class="bi bi-gear-fill"></i> Processos</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile">
        <div class="offcanvas-header"><h5 class="offcanvas-title">Menu</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div>
        <div class="offcanvas-body p-0">
            <div class="sidebar-header"><span class="user-name">Usuário</span><span class="user-email"><?php echo $logado; ?></span></div>
            <div class="d-flex flex-column">
                <a class="nav-link" href="sistema.php"><i class="bi bi-house-door"></i> Início</a>
                <a class="nav-link" href="sistema.php?page=documentos"><i class="bi bi-file-earmark-text"></i> Documentos</a>
                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link" href="sistema.php?page=usuarios"><i class="bi bi-people"></i> Usuários</a>
                <?php endif; ?>
                <?php if($perm == 3): ?>
                <a class="nav-link" href="sistema.php?page=processos"><i class="bi bi-gear-fill"></i> Processos</a>
                <?php endif; ?>
                <a class="nav-link text-danger mt-3" href="sair.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </div>
        </div>
    </div>

    <div class="main-content p-4 text-center">

        <?php if($pagina_atual == 'home'){ ?>
            <div class="mt-5"><i class="bi bi-shield-check" style="font-size: 5rem;"></i><h1 class="display-4 fw-bold">Bem vindo ao Sistema</h1></div>

        <?php } elseif($pagina_atual == 'usuarios'){ ?>
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-light">
                <h2>Gestão de Usuários</h2>
                <a href="cadastroInterno.php" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Novo Usuário</a>
            </div>
            <div class="box-search mb-4">
                <input type="search" class="form-control w-50" placeholder="Pesquisar..." id="pesquisar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>">
                <button onclick="searchData()" class="btn btn-success"><i class="bi bi-search"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table text-white table-bg align-middle">
                    <thead><tr><th>#</th><th>Nome</th><th>Email</th><th>Tel</th><th>Tipo</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php while($user_data = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $tipo = "Cliente";
                            if($user_data['permissao_id'] == 1) $tipo = "Admin";
                            elseif($user_data['permissao_id'] == 3) $tipo = "Gestor";
                            $souAdmin = ($perm == 1); $souGestor = ($perm == 3); $alvoEhCliente = ($user_data['permissao_id'] == 2);
                            echo "<tr><td>{$user_data['id']}</td><td>{$user_data['nome']}</td><td>{$user_data['email']}</td><td>{$user_data['telefone']}</td><td>$tipo</td><td>";
                            echo "<a class='btn btn-sm btn-primary' href='edit.php?id={$user_data['id']}'><i class='bi bi-pencil'></i></a> ";
                            
                            if($souAdmin || ($souGestor && $alvoEhCliente)){ 
                                echo "<a class='btn btn-sm btn-danger' href='#' onclick=\"confirmarExclusao(event, 'delete.php?id={$user_data['id']}')\"><i class='bi bi-trash-fill'></i></a>"; 
                            }
                            echo "</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>

        <?php } elseif($pagina_atual == 'processos'){ ?>
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-light">
                <h2>Gestão de Processos</h2>
                <a href="cadastroProcesso.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Novo Processo</a>
            </div>
            <div class="box-search mb-4">
                <input type="search" class="form-control w-50" placeholder="Pesquisar processos..." id="pesquisar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>">
                <button onclick="searchData()" class="btn btn-success"><i class="bi bi-search"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table text-white table-bg align-middle">
                    <thead><tr><th>#</th><th>Nº Processo</th><th>Cliente</th><th>Descrição</th><th>Status</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php while($proc = $stmtProcessos->fetch(PDO::FETCH_ASSOC)){
                            echo "<tr><td>{$proc['id']}</td><td><strong>{$proc['numero_processo']}</strong></td><td>".($proc['nome_cliente'] ? $proc['nome_cliente'] : '<i>Desconhecido</i>')."</td><td>{$proc['descricao']}</td><td>{$proc['status']}</td><td>";
                            echo "<a class='btn btn-sm btn-primary' href='editProcesso.php?id={$proc['id']}'><i class='bi bi-pencil'></i></a> ";
                            
                            echo "<a class='btn btn-sm btn-danger' href='#' onclick=\"confirmarExclusao(event, 'deleteProcesso.php?id={$proc['id']}')\"><i class='bi bi-trash-fill'></i></a>";
                            
                            echo "</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>

        <?php } elseif($pagina_atual == 'documentos'){ 
             $id_usuario_logado = $_SESSION['id_usuario'] ?? 0;
             if($perm == 1){ $sqlDocs = "SELECT d.*, u.nome as nome_usuario FROM documentos d JOIN usuarios u ON d.usuario_id = u.id ORDER BY d.data_upload DESC"; } 
             else { $sqlDocs = "SELECT d.*, u.nome as nome_usuario FROM documentos d JOIN usuarios u ON d.usuario_id = u.id WHERE d.usuario_id = $id_usuario_logado ORDER BY d.data_upload DESC"; }
             $stmtDocs = $pdo->prepare($sqlDocs); $stmtDocs->execute();
        ?>
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-light pb-3"><h2>Gestão de Documentos</h2><a href="cadastroDocumento.php" class="btn btn-success"><i class="bi bi-file-earmark-plus"></i> Novo Documento</a></div>
            <div class="table-responsive"><table class="table text-white table-bg"><thead><tr><th>Código</th><th>Tipo</th><th>Arquivo</th><th>Enviado por</th><th>Data</th><th>Ações</th></tr></thead><tbody>
                <?php if($stmtDocs->rowCount() == 0){ echo "<tr><td colspan='6'>Nenhum documento encontrado.</td></tr>"; } else {
                    while($doc = $stmtDocs->fetch(PDO::FETCH_ASSOC)){ $dataForm = date('d/m/Y H:i', strtotime($doc['data_upload']));
                    echo "<tr><td>{$doc['codigo_identificador']}</td><td>{$doc['tipo_documento']}</td><td>{$doc['caminho_arquivo']}</td><td>{$doc['nome_usuario']}</td><td>$dataForm</td><td>";
                    echo "<a href='uploads/{$doc['caminho_arquivo']}' target='_blank' class='btn btn-sm btn-light'><i class='bi bi-eye-fill text-success'></i></a>";
                    
                    if($perm == 1){ 
                        echo " <a class='btn btn-sm btn-danger' href='#' onclick=\"confirmarExclusao(event, 'deleteDoc.php?id={$doc['id']}')\"><i class='bi bi-trash-fill'></i></a>"; 
                    }
                    echo "</td></tr>"; } } ?>
            </tbody></table></div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        var search = document.getElementById('pesquisar');
        if(search){ search.addEventListener("keydown", function(event) { if (event.key === "Enter") { searchData(); } }); }
        function searchData(){ window.location = 'sistema.php?page=<?php echo $pagina_atual; ?>&busca='+search.value; }
        
        function confirmarExclusao(event, url) {
            event.preventDefault();

            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                background: '#1f1f1f',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');

            if(msg === 'deletado'){
                Swal.fire({
                    icon: 'success',
                    title: 'Excluído!',
                    text: 'O registro foi apagado com sucesso.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: 'limegreen'
                });
            } else if(msg === 'atualizado'){
                Swal.fire({
                    icon: 'success',
                    title: 'Atualizado!',
                    text: 'Dados salvos com sucesso.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: 'limegreen'
                });
            } else if(msg === 'cadastrado'){
                Swal.fire({
                    icon: 'success',
                    title: 'Cadastrado!',
                    text: 'Novo registro criado com sucesso.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: 'limegreen'
                });
            } else if(msg === 'sem_permissao'){
                Swal.fire({
                    icon: 'error',
                    title: 'Acesso Negado',
                    text: 'Você não tem permissão para realizar essa ação.',
                    background: '#1f1f1f',
                    color: '#fff',
                    confirmButtonColor: '#d33'
                });
            }
        }
    </script>
</body>
</html>