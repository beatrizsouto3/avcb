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

    $pagina_atual = isset($_GET['page']) ? $_GET['page'] : 'home';

    if($pagina_atual == 'usuarios' && $perm != 1 && $perm != 3){
        header('Location: sistema.php');
        exit;
    }

    if($pagina_atual == 'processos' && $perm != 1 && $perm != 3){
        header('Location: sistema.php');
        exit;
    }

    if($pagina_atual == 'usuarios'){
        $where = "";
        
        if($perm == 3){
            $where = " AND permissao_id = 2";
        }

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
        $sqlBase = "SELECT p.*, u.nome AS nome_cliente 
                    FROM processos p 
                    LEFT JOIN usuarios u ON p.cliente_id = u.id";

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
    
    <title>SISTEMA | AVCB</title>
    <style>
        body {
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .navbar-custom {
            background-color: rgba(0,0,0,0.2);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .sidebar-content {
            background-color: rgba(0, 0, 0, 0.2);
            height: 100%;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-name { font-weight: bold; font-size: 1.1rem; display: block; }
        .user-email { font-size: 0.85rem; opacity: 0.8; }
        .btn-logout-icon { color: white; float: right; font-size: 1.2rem; cursor: pointer; }
        
        .nav-link {
            color: white;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
            display: flex;
            align-items: center;
        }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background-color: rgba(0,0,0,0.2); border-left: 4px solid #fff; }
        .nav-link i { margin-right: 15px; font-size: 1.2rem; }

        .offcanvas {
            background-image: linear-gradient(to bottom, rgb(20, 70, 35), rgb(80, 220, 120));
            color: white;
            max-width: 80%;
        }
        
        @media (min-width: 992px) {
            .sidebar-col {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 100;
                padding: 0;
                width: 250px;
            }
            .main-content { margin-left: 250px; }
            .btn-menu-mobile { display: none; }
        }

        .table-bg { background: rgba(0,0,0,0.3); border-radius: 10px; }
        .box-search { display: flex; gap: 10px; }
        @media (max-width: 768px) { .box-search { flex-direction: column; } .w-25 { width: 100% !important; } }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom d-lg-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                <span class="navbar-toggler-icon"></span>
            </button>
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
                <a class="nav-link <?php echo ($pagina_atual == 'home') ? 'active' : ''; ?>" href="sistema.php">
                    <i class="bi bi-house-door"></i> Início
                </a>
                
                <a class="nav-link <?php echo ($pagina_atual == 'documentos') ? 'active' : ''; ?>" href="sistema.php?page=documentos">
                    <i class="bi bi-file-earmark-text"></i> Documentos
                </a>
                
                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link <?php echo ($pagina_atual == 'usuarios') ? 'active' : ''; ?>" href="sistema.php?page=usuarios">
                    <i class="bi bi-people"></i> Usuários
                </a>
                <?php endif; ?>

                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link <?php echo ($pagina_atual == 'processos') ? 'active' : ''; ?>" href="sistema.php?page=processos">
                    <i class="bi bi-gear-fill"></i> Processos
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
        <div class="offcanvas-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="offcanvas-title" id="sidebarMobileLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="sidebar-header">
                <span class="user-name">Usuário</span>
                <span class="user-email"><?php echo $logado; ?></span>
            </div>
            <div class="d-flex flex-column">
                <a class="nav-link" href="sistema.php"><i class="bi bi-house-door"></i> Início</a>
                <a class="nav-link" href="sistema.php?page=documentos"><i class="bi bi-file-earmark-text"></i> Documentos</a>
                
                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link" href="sistema.php?page=usuarios"><i class="bi bi-people"></i> Usuários</a>
                <?php endif; ?>

                <?php if($perm == 1 || $perm == 3): ?>
                <a class="nav-link" href="sistema.php?page=processos"><i class="bi bi-gear-fill"></i> Processos</a>
                <?php endif; ?>
                
                <a class="nav-link text-danger mt-3" href="sair.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </div>
        </div>
    </div>

    <div class="main-content p-4 text-center">
        
        <?php if(isset($_GET['msg'])){
            $msg = $_GET['msg'];
            $toastClass = "bg-secondary"; $toastMsg = "Ação realizada.";
            
            if($msg == 'deletado'){ $toastClass = "bg-danger"; $toastMsg = "Registro excluído com sucesso!"; }
            else if($msg == 'atualizado'){ $toastClass = "bg-success"; $toastMsg = "Dados atualizados com sucesso!"; }
            else if($msg == 'cadastrado'){ $toastClass = "bg-success"; $toastMsg = "Cadastro realizado com sucesso!"; }
            else if($msg == 'sem_permissao'){ $toastClass = "bg-warning text-dark"; $toastMsg = "Você não tem permissão para isso!"; }
        ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"><?php echo $toastMsg; ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if($pagina_atual == 'home'){ ?>
            <div class="mt-5">
                <i class="bi bi-shield-check" style="font-size: 5rem;"></i>
                <h1 class="display-4 fw-bold">Bem vindo ao Sistema</h1>
            </div>

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
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tel</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while($user_data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                
                                $tipo = "Cliente";
                                if($user_data['permissao_id'] == 1) $tipo = "Admin";
                                elseif($user_data['permissao_id'] == 3) $tipo = "Gestor";

                                $souAdmin = ($perm == 1);
                                $souGestor = ($perm == 3);
                                $alvoEhCliente = ($user_data['permissao_id'] == 2);

                                echo "<tr>";
                                echo "<td>{$user_data['id']}</td>";
                                echo "<td>{$user_data['nome']}</td>";
                                echo "<td>{$user_data['email']}</td>";
                                echo "<td>{$user_data['telefone']}</td>";
                                echo "<td>$tipo</td>";
                                
                                echo "<td>";
                                echo "<a class='btn btn-sm btn-primary' href='edit.php?id={$user_data['id']}' title='Editar'><i class='bi bi-pencil'></i></a> ";
                                
                                if($souAdmin || ($souGestor && $alvoEhCliente)){
                                    echo "<a class='btn btn-sm btn-danger' href='delete.php?id={$user_data['id']}' title='Excluir' onclick=\"return confirm('Tem certeza que deseja excluir este usuário?')\"><i class='bi bi-trash-fill'></i></a>";
                                }
                                
                                echo "</td>";
                                echo "</tr>";
                            } 
                        ?>
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
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nº Processo</th>
                            <th>Cliente</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while($proc = $stmtProcessos->fetch(PDO::FETCH_ASSOC)){
                                echo "<tr>";
                                echo "<td>{$proc['id']}</td>";
                                echo "<td><strong>{$proc['numero_processo']}</strong></td>";
                                echo "<td>".($proc['nome_cliente'] ? $proc['nome_cliente'] : '<i>Desconhecido</i>')."</td>";
                                echo "<td>{$proc['descricao']}</td>";
                                echo "<td>{$proc['status']}</td>";
                                echo "<td>";
                                
                                echo "<a class='btn btn-sm btn-primary' href='editProcesso.php?id={$proc['id']}' title='Editar'><i class='bi bi-pencil'></i></a> ";
                                
                                echo "<a class='btn btn-sm btn-danger' href='deleteProcesso.php?id={$proc['id']}' title='Excluir' onclick=\"return confirm('Tem certeza que deseja apagar este processo?')\"><i class='bi bi-trash-fill'></i></a>";
                                
                                echo "</td>";
                                echo "</tr>";
                            } 
                        ?>
                    </tbody>
                </table>
            </div>

        <?php } elseif($pagina_atual == 'documentos'){ 
            
            $id_usuario_logado = $_SESSION['id_usuario'] ?? 0;
            
            if($perm == 1){
                $sqlDocs = "SELECT d.*, u.nome as nome_usuario 
                            FROM documentos d 
                            JOIN usuarios u ON d.usuario_id = u.id 
                            ORDER BY d.data_upload DESC";
            } else {
                $sqlDocs = "SELECT d.*, u.nome as nome_usuario 
                            FROM documentos d 
                            JOIN usuarios u ON d.usuario_id = u.id 
                            WHERE d.usuario_id = $id_usuario_logado
                            ORDER BY d.data_upload DESC";
            }
            $stmtDocs = $pdo->prepare($sqlDocs);
            $stmtDocs->execute();
        ?>
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-light pb-3">
                <h2>Gestão de Documentos</h2>
                <a href="cadastroDocumento.php" class="btn btn-success">
                    <i class="bi bi-file-earmark-plus"></i> Novo Documento
                </a>
            </div>

            <div class="table-responsive">
                <table class="table text-white table-bg">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Arquivo</th>
                            <th>Enviado por</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($stmtDocs->rowCount() == 0){
                                echo "<tr><td colspan='6'>Nenhum documento encontrado.</td></tr>";
                            } else {
                                while($doc = $stmtDocs->fetch(PDO::FETCH_ASSOC)){
                                    $dataForm = date('d/m/Y H:i', strtotime($doc['data_upload']));
                                    echo "<tr>";
                                    echo "<td>{$doc['codigo_identificador']}</td>";
                                    echo "<td>{$doc['tipo_documento']}</td>";
                                    echo "<td>{$doc['caminho_arquivo']}</td>";
                                    echo "<td>{$doc['nome_usuario']}</td>";
                                    echo "<td>$dataForm</td>";
                                    echo "<td>
                                        <a href='uploads/{$doc['caminho_arquivo']}' target='_blank' class='btn btn-sm btn-light' title='Visualizar'>
                                            <i class='bi bi-eye-fill text-success'></i>
                                        </a>";
                                        
                                        if($perm == 1){
                                            echo " <a class='btn btn-sm btn-danger' href='deleteDoc.php?id={$doc['id']}' title='Excluir' onclick=\"return confirm('Tem certeza?')\">
                                                <i class='bi bi-trash-fill'></i>
                                            </a>";
                                        }
                                        
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var search = document.getElementById('pesquisar');
        if(search){
            search.addEventListener("keydown", function(event) {
                if (event.key === "Enter") { searchData(); }
            });
        }
        function searchData(){
            window.location = 'sistema.php?page=<?php echo $pagina_atual; ?>&busca='+search.value;
        }
        
        window.onload = function() {
            var toastEl = document.getElementById('liveToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            }
        }
    </script>
</body>
</html>