<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    }
    $logado = $_SESSION['email'];

    $pagina_atual = isset($_GET['page']) ? $_GET['page'] : 'home';

    if($pagina_atual == 'usuarios' && $_SESSION['permissao'] != 1){
        header('Location: sistema.php');
        exit;
    }

    if($pagina_atual == 'usuarios'){
        
        if(!empty($_GET['busca']))
        {
            $data = $_GET['busca'];
            $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

            switch($filtro){
                case 'nome':
                    $sql = "SELECT * FROM usuarios WHERE nome ILIKE '%$data%' ORDER BY id DESC";
                    break;
                case 'email':
                    $sql = "SELECT * FROM usuarios WHERE email ILIKE '%$data%' ORDER BY id DESC";
                    break;
                case 'id':
                    if(is_numeric($data)){
                        $sql = "SELECT * FROM usuarios WHERE id = $data ORDER BY id DESC";
                    } else {
                        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
                    }
                    break;
                default:
                    $sql = "SELECT * FROM usuarios WHERE id::text LIKE '%$data%' OR nome ILIKE '%$data%' OR email ILIKE '%$data%' ORDER BY id DESC";
                    break;
            }
        }
        else
        {
            $sql = "SELECT * FROM usuarios ORDER BY id DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
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
        body{
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            color: white;
            overflow-x: hidden;
        }
        .table-bg{
            background: rgba(0,0,0,0.3);
            border-radius: 15px 15px 0 0;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: rgba(0, 0, 0, 0.2);
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                min-height: auto !important;
                border-right: none;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .box-search {
                flex-direction: column;
            }
            .form-select-custom, #pesquisar, .btn-success {
                width: 100% !important;
                margin-bottom: 10px;
            }
        }

        .nav-link {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: limegreen;
        }
        .nav-link.active {
            background-color: limegreen;
            color: black;
        }
        .box-search{
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .form-select-custom {
            width: 150px;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
</head>
<body>

    <?php if(isset($_GET['msg'])){
        $msg = $_GET['msg'];
        $toastClass = "bg-secondary"; $toastMsg = "Ação realizada.";
        if($msg == 'deletado'){ $toastClass = "bg-danger"; $toastMsg = "Usuário excluído com sucesso!"; }
        else if($msg == 'atualizado'){ $toastClass = "bg-success"; $toastMsg = "Dados atualizados com sucesso!"; }
        else if($msg == 'cadastrado'){ $toastClass = "bg-success"; $toastMsg = "Usuário cadastrado com sucesso!"; }
    ?>
    <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" style="font-size: 16px;">
                    <i class="bi bi-info-circle-fill me-2"></i> <?php echo $toastMsg; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php } ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="sistema.php">Sistema | AVCB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex d-none d-lg-block"> <a href="sair.php" class="btn btn-danger me-2">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($pagina_atual == 'home') ? 'active' : ''; ?>" href="sistema.php">
                                <i class="bi bi-house-door-fill me-2"></i> Início
                            </a>
                        </li>
                        <?php if(isset($_SESSION['permissao']) && $_SESSION['permissao'] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($pagina_atual == 'usuarios') ? 'active' : ''; ?>" href="sistema.php?page=usuarios">
                                    <i class="bi bi-people-fill me-2"></i> Usuários
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item d-md-none mt-3">
                             <a class="nav-link text-danger" href="sair.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 text-center">
                
                <?php if($pagina_atual == 'home'){ ?>
                    <div class="mt-4">
                        <i class="bi bi-shield-check" style="font-size: 5rem;"></i>
                        <h1 class="display-4">Bem vindo ao Sistema</h1>
                        <h3>Usuário logado: <u><?php echo $logado; ?></u></h3>
                    </div>

                <?php } elseif($pagina_atual == 'usuarios'){ ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom border-light">
                        <h2>Gestão de Usuários</h2>
                        <a href="cadastroAdmin.php" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Novo Usuário</a>
                    </div>

                    <div class="box-search mb-4">
                        <select id="filtro" class="form-select form-select-custom">
                            <option value="todos" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'todos') ? 'selected' : ''; ?>>Geral</option>
                            <option value="nome" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'nome') ? 'selected' : ''; ?>>Nome</option>
                            <option value="email" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'email') ? 'selected' : ''; ?>>Email</option>
                            <option value="id" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        </select>

                        <input type="search" class="form-control w-25" placeholder="Pesquisar..." id="pesquisar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>">
                        
                        <button onclick="searchData()" class="btn btn-success">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-white table-bg">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Tel</th>
                                    <th scope="col">Nível</th> 
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($user_data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        echo "<tr>";
                                        echo "<td>" . $user_data['id'] . "</td>";
                                        echo "<td>" . $user_data['nome'] . "</td>";
                                        echo "<td>" . $user_data['email'] . "</td>";
                                        echo "<td>" . $user_data['telefone'] . "</td>";
                                        echo "<td>" . ($user_data['permissao_id'] == 1 ? 'Admin' : 'Comum') . "</td>";
                                        echo "<td>
                                            <a class='btn btn-sm btn-success' href='edit.php?id=$user_data[id]'>
                                                <i class='bi bi-pencil'></i>
                                            </a> 
                                            <a class='btn btn-sm btn-danger' href='delete.php?id=$user_data[id]'>
                                                <i class='bi bi-trash-fill'></i>
                                            </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var search = document.getElementById('pesquisar');
        var filtro = document.getElementById('filtro');

        if(search){
            search.addEventListener("keydown", function(event) {
                if (event.key === "Enter") { searchData(); }
            });
        }
        function searchData(){
            window.location = 'sistema.php?page=usuarios&busca='+search.value+'&filtro='+filtro.value;
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