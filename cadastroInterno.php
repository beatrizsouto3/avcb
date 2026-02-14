<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        header('Location: sistema.php');
        exit;
    }
    if($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3){
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
            $representante_email = $_POST['representante_email'];
            
            $perfil_cliente = $_POST['perfil_cliente']; 
            $origem_contato = $_POST['origem_contato'];
            $observacoes = $_POST['observacoes'];
        } else {
            $cpf_cnpj = $_POST['cpf'];
            $rg = $_POST['rg'];
            $inscricao_estadual = $representante_nome = $representante_cpf = $representante_cargo = $representante_email = "";
            $perfil_cliente = $origem_contato = $observacoes = "";
        }

        $cep = $_POST['cep']; $logradouro = $_POST['logradouro']; $numero = $_POST['numero'];
        $complemento = $_POST['complemento']; $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade']; $estado = $_POST['estado'];
        $ponto_referencia = $_POST['ponto_referencia'];
        
        $telefone = $_POST['telefone']; $celular = $_POST['celular']; $email = $_POST['email'];
        
        if($_SESSION['permissao'] == 1){
            $permissao_id = $_POST['permissao_id'];
        } else {
            $permissao_id = 2;
        }

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
                representante_nome, representante_cpf, representante_cargo, representante_email,
                cep, logradouro, numero, complemento, bairro, cidade, estado, ponto_referencia,
                telefone, celular, email, senha,
                perfil_cliente, origem_contato, observacoes,
                permissao_id, primeiro_acesso
            ) VALUES (
                :nome, :nome_fantasia, :data_referencia, :tipo_cliente, :cpf_cnpj, :rg, :inscricao_estadual,
                :representante_nome, :representante_cpf, :representante_cargo, :representante_email,
                :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, :ponto_referencia,
                :telefone, :celular, :email, :senha,
                :perfil_cliente, :origem_contato, :observacoes,
                :permissao_id, 'true'
            )";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome, ':nome_fantasia' => $nome_fantasia, ':data_referencia' => $data_referencia,
                    ':tipo_cliente' => $tipo_cliente, ':cpf_cnpj' => $cpf_cnpj, ':rg' => $rg, ':inscricao_estadual' => $inscricao_estadual,
                    ':representante_nome' => $representante_nome, ':representante_cpf' => $representante_cpf, 
                    ':representante_cargo' => $representante_cargo, ':representante_email' => $representante_email,
                    ':cep' => $cep, ':logradouro' => $logradouro, ':numero' => $numero, ':complemento' => $complemento,
                    ':bairro' => $bairro, ':cidade' => $cidade, ':estado' => $estado, ':ponto_referencia' => $ponto_referencia,
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
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Cadastro | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; transition: all 0.3s ease; }
        .container { max-width: 900px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 1rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .btn-custom { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">NOVO USUÁRIO</h2>
            <a href="sistema.php?page=usuarios" class="btn btn-outline-secondary border-0"><i class="bi bi-x-lg"></i></a>
        </div>

        <?php if($erroEmail): ?>
            <div class="alert alert-danger">⚠ Este e-mail já está cadastrado no sistema.</div>
        <?php endif; ?>

        <form action="cadastroInterno.php" method="POST">
            <fieldset>
                <legend>Tipo de Perfil</legend>
                <div class="d-flex gap-4 justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PF" id="pf" checked onclick="toggleTipo('PF')">
                        <label class="form-check-label" for="pf">Pessoa Física</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PJ" id="pj" onclick="toggleTipo('PJ')">
                        <label class="form-check-label" for="pj">Pessoa Jurídica</label>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Dados Gerais</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome / Razão Social *</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Nasc. / Fundação</label>
                        <input type="date" name="data_referencia" class="form-control">
                    </div>
                    
                    <div class="col-md-4 area_pf">
                        <label class="form-label">CPF *</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" oninput="mascara(this, 'cpf')">
                    </div>
                    <div class="col-md-4 area_pf">
                        <label class="form-label">RG</label>
                        <input type="text" name="rg" class="form-control">
                    </div>

                    <div class="col-md-4 area_pj hidden">
                        <label class="form-label">CNPJ *</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control" oninput="mascara(this, 'cnpj')">
                    </div>
                    <div class="col-md-4 area_pj hidden">
                        <label class="form-label">Insc. Estadual</label>
                        <input type="text" name="inscricao_estadual" class="form-control">
                    </div>
                </div>

                <div class="area_pj hidden mt-4 border-top pt-4">
                    <h6 class="fw-bold mb-3">Representante Legal</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nome</label><input type="text" name="representante_nome" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">CPF *</label><input type="text" name="representante_cpf" id="representante_cpf" class="form-control" oninput="mascara(this, 'cpf')"></div>
                        <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="representante_cargo" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" name="representante_email" id="representante_email" class="form-control"></div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Endereço</legend>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">CEP</label><input type="text" name="cep" class="form-control" oninput="mascara(this, 'cep')"></div>
                    <div class="col-md-6"><label class="form-label">Logradouro</label><input type="text" name="logradouro" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Número</label><input type="text" name="numero" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Bairro</label><input type="text" name="bairro" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Cidade</label><input type="text" name="cidade" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label">UF</label><input type="text" name="estado" class="form-control" maxlength="2"></div>
                    <div class="col-md-12"><label class="form-label">Complemento / Ref.</label><input type="text" name="complemento" class="form-control"></div>
                    <input type="hidden" name="ponto_referencia" value="">
                </div>
            </fieldset>

            <fieldset>
                <legend>Acesso e Contato</legend>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">E-mail (Login) *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="col-md-3"><label class="form-label">Celular *</label><input type="text" name="celular" class="form-control" oninput="mascara(this, 'tel')" required></div>
                    <div class="col-md-3"><label class="form-label">Telefone</label><input type="text" name="telefone" class="form-control" oninput="mascara(this, 'tel')"></div>
                    <div class="col-md-12">
                        <label class="form-label">Nível de Acesso</label>
                        <?php if($_SESSION['permissao'] == 1): ?>
                            <select name="permissao_id" class="form-select">
                                <option value="2">Cliente</option>
                                <option value="3">Gestor</option>
                                <option value="1">Administrador</option>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control" value="Cliente" disabled>
                            <input type="hidden" name="permissao_id" value="2">
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>

            <fieldset id="area_comercial" class="hidden">
                <legend>Info. Comerciais</legend>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Perfil</label><select name="perfil_cliente" class="form-select"><option>Residencial</option><option>Comercial</option></select></div>
                    <div class="col-md-6"><label class="form-label">Origem</label><select name="origem_contato" class="form-select"><option>Indicação</option><option>Internet</option></select></div>
                    <div class="col-md-12"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control"></textarea></div>
                </div>
            </fieldset>

            <button type="submit" name="submit" class="btn-custom mb-5">Cadastrar Usuário</button>
        </form>
    </div>

    <script>
        function toggleTipo(tipo) {
            const pf = document.querySelectorAll('.area_pf');
            const pj = document.querySelectorAll('.area_pj');
            const comercial = document.getElementById('area_comercial');
            
            if(tipo === 'PF'){
                pf.forEach(el => el.classList.remove('hidden'));
                pj.forEach(el => el.classList.add('hidden'));
                comercial.classList.add('hidden');
                document.getElementById('cpf').required = true;
                document.getElementById('cnpj').required = false;
            } else {
                pf.forEach(el => el.classList.add('hidden'));
                pj.forEach(el => el.classList.remove('hidden'));
                comercial.classList.remove('hidden');
                document.getElementById('cpf').required = false;
                document.getElementById('cnpj').required = true;
            }
        }

        function mascara(i, t) {
            let v = i.value.replace(/\D/g, "");
            if (t == 'cpf') v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            if (t == 'cnpj') v = v.replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2");
            if (t == 'tel') v = v.replace(/^(\d{2})(\d)/g, "($1) $2").replace(/(\d)(\d{4})$/, "$1-$2");
            if (t == 'cep') v = v.replace(/^(\d{5})(\d)/, "$1-$2");
            i.value = v;
        }

        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('tema') || 'dark');
    </script>
</body>
</html>