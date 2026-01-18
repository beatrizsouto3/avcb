<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
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
                case 'cidade':
                    $sql = "SELECT * FROM usuarios WHERE cidade ILIKE '%$data%' ORDER BY id DESC";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>SISTEMA | AVCB</title>
    <style>
        body{
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
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
        .nav-link {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: dodgerblue;
        }
        .nav-link.active {
            background-color: dodgerblue;
            color: white;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="sistema.php">Sistema | AVCB</a>
            <div class="d-flex">
                <a href="sair.php" class="btn btn-danger me-2">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block sidebar py-4">
                <div class="position-sticky">
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
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4 py-4 text-center">
                <?php if($pagina_atual == 'home'){ ?>
                    <div class="mt-5">
                        <i class="bi bi-shield-check" style="font-size: 5rem;"></i>
                        <h1 class="display-4">Bem vindo ao Sistema</h1>
                        <h3>Usuário logado: <u><?php echo $logado; ?></u></h3>
                    </div>
                <?php } elseif($pagina_atual == 'usuarios'){ ?>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2>Gestão de Usuários</h2>
                        <a href="cadastroAdmin.php" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Novo Usuário</a>
                    </div>

                    <div class="box-search mb-4">
                        <select id="filtro" class="form-select form-select-custom">
                            <option value="todos" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'todos') ? 'selected' : ''; ?>>Geral</option>
                            <option value="nome" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'nome') ? 'selected' : ''; ?>>Nome</option>
                            <option value="email" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'email') ? 'selected' : ''; ?>>Email</option>
                            <option value="cidade" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'cidade') ? 'selected' : ''; ?>>Cidade</option>
                            <option value="id" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        </select>

                        <input type="search" class="form-control w-25" placeholder="Pesquisar..." id="pesquisar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>">
                        
                        <button onclick="searchData()" class="btn btn-primary">
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
                                    <th scope="col">Telefone</th>
                                    <th scope="col">Permissão</th> 
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
                                            <a class='btn btn-sm btn-primary' href='edit.php?id=$user_data[id]' title='Editar'>
                                                <i class='bi bi-pencil'></i>
                                            </a> 
                                            <a class='btn btn-sm btn-danger' href='delete.php?id=$user_data[id]' title='Deletar'>
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

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                searchData();
            }
        });

        function searchData(){
            window.location = 'sistema.php?page=usuarios&busca='+search.value+'&filtro='+filtro.value;
        }
    </script>
</body>
</html>