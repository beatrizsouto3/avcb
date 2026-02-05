<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1)){
        header('Location: sistema.php');
        exit;
    }

    $erroEmail = false;

    if (isset($_POST['submit'])) {
        $tipo_cliente = $_POST['tipo_cliente'];
        $nome = $_POST['nome'];
        $nome_fantasia = $_POST['nome_fantasia'];
        $data_referencia = !empty($_POST['data_referencia']) ? $_POST['data_referencia'] : null;
        
        if($tipo_cliente == 'PJ'){
            $cpf_cnpj = $_POST['cnpj'];
            $rg = ""; 
            $inscricao_estadual = $_POST['inscricao_estadual'];
            $representante_nome = $_POST['representante_nome'];
            $representante_cpf = $_POST['representante_cpf'];
            $representante_cargo = $_POST['representante_cargo'];
        } else {
            $cpf_cnpj = $_POST['cpf'];
            $rg = $_POST['rg'];
            $inscricao_estadual = $representante_nome = $representante_cpf = $representante_cargo = "";
        }

        $cep = $_POST['cep']; $logradouro = $_POST['logradouro']; $numero = $_POST['numero'];
        $complemento = $_POST['complemento']; $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade']; $estado = $_POST['estado'];

        $telefone = $_POST['telefone']; $celular = $_POST['celular']; $email = $_POST['email'];
        $perfil_cliente = $_POST['perfil_cliente']; $origem_contato = $_POST['origem_contato'];
        $observacoes = $_POST['observacoes'];
        $permissao_id = $_POST['permissao_id'];

        $sqlVerifica = "SELECT id FROM usuarios WHERE email = :email";
        $stmtVerifica = $pdo->prepare($sqlVerifica);
        $stmtVerifica->execute([':email' => $email]);

        if($stmtVerifica->rowCount() > 0){
            $erroEmail = true;
        }
        else {
            $senhaLimpa = substr(md5(time()), 0, 8);
            $senha = md5($senhaLimpa); 
            
            $sql = "INSERT INTO usuarios (
                nome, nome_fantasia, data_nascimento_fundacao, tipo_cliente, cpf_cnpj, rg, inscricao_estadual,
                representante_nome, representante_cpf, representante_cargo,
                cep, logradouro, numero, complemento, bairro, cidade, estado,
                telefone, celular, email, senha,
                perfil_cliente, origem_contato, observacoes,
                permissao_id, primeiro_acesso
            ) VALUES (
                :nome, :nome_fantasia, :data_referencia, :tipo_cliente, :cpf_cnpj, :rg, :inscricao_estadual,
                :representante_nome, :representante_cpf, :representante_cargo,
                :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado,
                :telefone, :celular, :email, :senha,
                :perfil_cliente, :origem_contato, :observacoes,
                :permissao_id, 'true'
            )";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome, ':nome_fantasia' => $nome_fantasia, ':data_referencia' => $data_referencia,
                    ':tipo_cliente' => $tipo_cliente, ':cpf_cnpj' => $cpf_cnpj, ':rg' => $rg, ':inscricao_estadual' => $inscricao_estadual,
                    ':representante_nome' => $representante_nome, ':representante_cpf' => $representante_cpf, ':representante_cargo' => $representante_cargo,
                    ':cep' => $cep, ':logradouro' => $logradouro, ':numero' => $numero, ':complemento' => $complemento,
                    ':bairro' => $bairro, ':cidade' => $cidade, ':estado' => $estado,
                    ':telefone' => $telefone, ':celular' => $celular, ':email' => $email, ':senha' => $senha,
                    ':perfil_cliente' => $perfil_cliente, ':origem_contato' => $origem_contato,
                    ':observacoes' => $observacoes, ':permissao_id' => $permissao_id
                ]);
                
                include_once('enviarEmail.php');
                enviarEmailBoasVindas($nome, $email, $senhaLimpa);
                header('Location: sistema.php?page=usuarios&msg=cadastrado');
                exit;

            } catch (PDOException $e) {
                echo "<script>alert('Erro no sistema: " . $e->getMessage() . "');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastro Admin</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            min-height: 100vh;
            padding: 20px;
            color: white;
        }
        .container-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            max-width: 900px;
            margin: auto;
        }
        fieldset { border: 1px solid limegreen; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        legend { 
            float: none; width: auto; background-color: limegreen; 
            padding: 5px 15px; border-radius: 5px; color: black; font-weight: bold; font-size: 1rem;
        }
        label { font-weight: bold; margin-bottom: 5px; display: block; font-size: 0.9rem; }
        .form-control, .form-select {
            background: rgba(255,255,255,0.9);
            border: none; margin-bottom: 15px;
        }
        .btn-custom {
            background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34));
            width: 100%; border: none; padding: 15px; color: white; font-size: 16px;
            cursor: pointer; border-radius: 10px; font-weight: bold; margin-top: 10px;
        }
        .hidden { display: none; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-half { flex: 1 1 45%; }
        .col-full { flex: 1 1 100%; }
        .msg-erro { background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; text-align: center; }
    </style>
    
    <script>
        function toggleTipoCliente(tipo) {
            document.getElementById('area_pf').classList.add('hidden');
            document.getElementById('area_pj').classList.add('hidden');
            
            if(tipo === 'PF'){
                document.getElementById('area_pf').classList.remove('hidden');
                document.getElementById('cpf').required = true;
                document.getElementById('cnpj').required = false;
            } else {
                document.getElementById('area_pj').classList.remove('hidden');
                document.getElementById('cpf').required = false;
                document.getElementById('cnpj').required = true;
            }
        }
        function mascara(i, t) {
            var v = i.value;
            v = v.replace(/\D/g, "");

            if (t == 'cpf') {
                v = v.replace(/(\d{3})(\d)/, "$1.$2");
                v = v.replace(/(\d{3})(\d)/, "$1.$2");
                v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                i.setAttribute("maxlength", "14");
            }
            else if (t == 'cnpj') {
                v = v.replace(/^(\d{2})(\d)/, "$1.$2");
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
                v = v.replace(/(\d{4})(\d)/, "$1-$2");
                i.setAttribute("maxlength", "18");
            }
            else if (t == 'tel') {
                i.setAttribute("maxlength", "15");
                v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
                v = v.replace(/(\d)(\d{4})$/, "$1-$2");
            }
            else if (t == 'cep') {
                v = v.replace(/^(\d{5})(\d)/, "$1-$2");
                i.setAttribute("maxlength", "9");
            }
            i.value = v;
        }
    </script>
</head>
<body>

<div class="container-box">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Novo Usuário (Admin)</h2>
        <a href="sistema.php?page=usuarios" style="color:white; text-decoration:none; border:1px solid white; padding:5px 10px; border-radius:5px;">Voltar</a>
    </div>
    <hr>

    <?php if($erroEmail): ?>
        <div class="msg-erro">⚠ E-mail já cadastrado!</div><br>
    <?php endif; ?>

    <form action="cadastroAdmin.php" method="POST">
        <fieldset>
            <legend>Tipo de Cliente</legend>
            <div style="display:flex; gap:20px; justify-content:center;">
                <label style="cursor:pointer;"><input type="radio" name="tipo_cliente" value="PF" checked onclick="toggleTipoCliente('PF')"> Pessoa Física</label>
                <label style="cursor:pointer;"><input type="radio" name="tipo_cliente" value="PJ" onclick="toggleTipoCliente('PJ')"> Pessoa Jurídica</label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dados Gerais</legend>
            <div class="row">
                <div class="col-half"><label>Nome / Razão Social *</label><input type="text" name="nome" class="form-control" required></div>
                <div class="col-half"><label>Nome Fantasia</label><input type="text" name="nome_fantasia" class="form-control"></div>
                <div class="col-half"><label>Data Nascimento / Fundação</label><input type="date" name="data_referencia" class="form-control"></div>
            </div>
            <div id="area_pf">
                <div class="row">
                    <div class="col-half"><label>CPF *</label><input type="text" name="cpf" id="cpf" class="form-control" oninput="mascara(this, 'cpf')"></div>
                    <div class="col-half"><label>RG</label><input type="text" name="rg" class="form-control"></div>
                </div>
            </div>
            <div id="area_pj" class="hidden">
                <div class="row">
                    <div class="col-half"><label>CNPJ *</label><input type="text" name="cnpj" id="cnpj" class="form-control" oninput="mascara(this, 'cnpj')"></div>
                    <div class="col-half"><label>Inscrição Estadual</label><input type="text" name="inscricao_estadual" class="form-control"></div>
                </div>
                <h4 style="margin-top:15px; color:limegreen; font-size:1rem;">Representante Legal</h4>
                <div class="row">
                    <div class="col-half"><label>Nome</label><input type="text" name="representante_nome" class="form-control"></div>
                    <div class="col-half"><label>CPF</label><input type="text" name="representante_cpf" class="form-control" oninput="mascara(this, 'cpf')"></div>
                    <div class="col-half"><label>Cargo</label><input type="text" name="representante_cargo" class="form-control"></div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Endereço</legend>
            <div class="row">
                <div class="col-half"><label>CEP</label><input type="text" name="cep" class="form-control" oninput="mascara(this, 'cep')"></div>
                <div class="col-half"><label>Logradouro</label><input type="text" name="logradouro" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-half"><label>Número</label><input type="text" name="numero" class="form-control"></div>
                <div class="col-half"><label>Bairro</label><input type="text" name="bairro" class="form-control"></div>
                <div class="col-half"><label>Cidade</label><input type="text" name="cidade" class="form-control"></div>
                <div class="col-half"><label>UF</label><input type="text" name="estado" class="form-control" maxlength="2"></div>
                <div class="col-half"><label>Complemento</label><input type="text" name="complemento" class="form-control"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Acesso e Contato</legend>
            <div class="row">
                <div class="col-half"><label>E-mail *</label><input type="email" name="email" class="form-control" required></div>
                <div class="col-half"><label>Celular / Zap *</label><input type="text" name="celular" class="form-control" oninput="mascara(this, 'tel')" required></div>
                <div class="col-half"><label>Telefone Fixo</label><input type="text" name="telefone" class="form-control" oninput="mascara(this, 'tel')"></div>
            </div>
            <div class="row">
                <div class="col-full">
                    <label>Nível de Acesso</label>
                    <select name="permissao_id" class="form-select">
                        <option value="2">Cliente</option>
                        <option value="3">Gestor</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Info. Comerciais</legend>
            <div class="row">
                <div class="col-half"><label>Perfil</label><select name="perfil_cliente" class="form-select"><option>Residencial</option><option>Comercial</option></select></div>
                <div class="col-half"><label>Origem</label><select name="origem_contato" class="form-select"><option>Indicação</option><option>Internet</option></select></div>
                <div class="col-full"><label>Observações</label><textarea name="observacoes" class="form-control"></textarea></div>
            </div>
        </fieldset>

        <button type="submit" name="submit" class="btn-custom">Cadastrar Usuário</button>
    </form>
</div>
</body>
</html>