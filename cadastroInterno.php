<?php
    ob_start(); 
    session_start();
    include_once('config.php');
    include_once('enviarEmail.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true)){
        header('Location: sistema.php');
        exit;
    }
    
    $erroEmail = false;

    if (isset($_POST['submit'])) {
        $tipo_cliente = $_POST['tipo_cliente'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $celular = $_POST['celular'];
        $permissao_id = $_POST['permissao_id'];

        $data_referencia = !empty($_POST['data_referencia']) ? $_POST['data_referencia'] : null;

        if($tipo_cliente == 'PF'){
            $cpf_cnpj = $_POST['cpf'];
            $rep_nome = $rep_cpf = null;
            if(empty($nome) || empty($cpf_cnpj) || empty($email) || empty($celular)){
                die("Erro: Preencha os campos obrigatórios de PF.");
            }
        } else {
            $cpf_cnpj = $_POST['cnpj'];
            $rep_nome = $_POST['representante_nome'];
            $rep_cpf = $_POST['representante_cpf'];
            if(empty($nome) || empty($cpf_cnpj) || empty($rep_nome) || empty($rep_cpf) || empty($email) || empty($celular)){
                die("Erro: Preencha os campos obrigatórios de PJ.");
            }
        }

        $sqlVerifica = "SELECT id FROM usuarios WHERE email = :email";
        $stmtVerifica = $pdo->prepare($sqlVerifica);
        $stmtVerifica->execute([':email' => $email]);

        if($stmtVerifica->rowCount() > 0){
            $erroEmail = true;
        } else {
            $senhaLimpa = substr(md5(time()), 0, 8);
            $senha = md5($senhaLimpa); 
            
            try {
                $sql = "INSERT INTO usuarios (
                    nome, nome_fantasia, data_nascimento_fundacao, tipo_cliente, cpf_cnpj, rg, inscricao_estadual,
                    representante_nome, representante_cpf, representante_cargo, representante_email,
                    cep, logradouro, numero, complemento, bairro, cidade, estado, ponto_referencia,
                    telefone, celular, email, senha, permissao_id, primeiro_acesso
                ) VALUES (
                    :nome, :nome_fantasia, :data_ref, :tipo, :id_fiscal, :rg, :ie,
                    :rep_n, :rep_c, :rep_cargo, :rep_email,
                    :cep, :log, :num, :comp, :bairro, :cid, :est, :pref,
                    :tel, :cel, :email, :pass, :perm, 'true'
                )";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome, 
                    ':nome_fantasia' => $_POST['nome_fantasia'], 
                    ':data_ref' => $data_referencia,
                    ':tipo' => $tipo_cliente, 
                    ':id_fiscal' => $cpf_cnpj, 
                    ':rg' => $_POST['rg'], 
                    ':ie' => $_POST['inscricao_estadual'],
                    ':rep_n' => $rep_nome, 
                    ':rep_c' => $rep_cpf, 
                    ':rep_cargo' => $_POST['representante_cargo'], 
                    ':rep_email' => $_POST['representante_email'],
                    ':cep' => $_POST['cep'], 
                    ':log' => $_POST['logradouro'], 
                    ':num' => $_POST['numero'], 
                    ':comp' => $_POST['complemento'] ?? '', 
                    ':bairro' => $_POST['bairro'], 
                    ':cid' => $_POST['cidade'], 
                    ':est' => $_POST['estado'], 
                    ':pref' => $_POST['ponto_referencia'],
                    ':tel' => $_POST['telefone'], 
                    ':cel' => $celular, 
                    ':email' => $email, 
                    ':pass' => $senha, 
                    ':perm' => $permissao_id
                ]);

                @enviarEmailBoasVindas($nome, $email, $senhaLimpa);
                header('Location: sistema.php?page=usuarios&msg=cadastrado');
                ob_end_flush();
                exit;
            } catch (Exception $e) {
                echo "Erro no banco: " . $e->getMessage();
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
    <title>Novo Usuário | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; }
        .container { max-width: 900px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .required-label::after { content: " *"; color: #dc3545; }
        .btn-custom { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0 text-uppercase">Cadastro de Usuário</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" id="btn-tema"><i class="bi bi-moon-stars-fill"></i> TEMA</button>
                <a href="sistema.php?page=usuarios" class="btn btn-outline-danger btn-sm">FECHAR</a>
            </div>
        </div>

        <?php if($erroEmail): ?>
            <div class="alert alert-danger">⚠ Este e-mail já existe no sistema.</div>
        <?php endif; ?>

        <form action="cadastroInterno.php" method="POST" id="formCadastro">
            <fieldset>
                <legend>Tipo de Cadastro</legend>
                <div class="d-flex gap-5 justify-content-center py-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PF" id="pf" checked onclick="toggleTipo('PF')">
                        <label class="form-check-label fw-bold" for="pf">PESSOA FÍSICA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PJ" id="pj" onclick="toggleTipo('PJ')">
                        <label class="form-check-label fw-bold" for="pj">PESSOA JURÍDICA</label>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Informações Gerais</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">Nome / Razão Social</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nome Fantasia (Opcional)</label>
                        <input type="text" name="nome_fantasia" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Nasc. / Fundação</label>
                        <input type="date" name="data_referencia" class="form-control">
                    </div>
                    
                    <div class="col-md-4 area_pf">
                        <label class="form-label required-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control req-pf" oninput="mascara(this, 'cpf')">
                    </div>
                    <div class="col-md-4 area_pf">
                        <label class="form-label">RG</label>
                        <input type="text" name="rg" class="form-control">
                    </div>

                    <div class="col-md-4 area_pj hidden">
                        <label class="form-label required-label">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control req-pj" oninput="mascara(this, 'cnpj')">
                    </div>
                    <div class="col-md-4 area_pj hidden">
                        <label class="form-label">Insc. Estadual</label>
                        <input type="text" name="inscricao_estadual" class="form-control">
                    </div>
                </div>

                <div class="area_pj hidden mt-4 border-top pt-4">
                    <h6 class="fw-bold mb-3 text-uppercase small opacity-75">Representante Legal</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required-label">Nome do Representante</label>
                            <input type="text" name="representante_nome" class="form-control req-pj">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-label">CPF do Representante</label>
                            <input type="text" name="representante_cpf" class="form-control req-pj" oninput="mascara(this, 'cpf')">
                        </div>
                        <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="representante_cargo" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">E-mail Corporativo</label><input type="email" name="representante_email" class="form-control"></div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Localização (Opcional)</legend>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">CEP</label><input type="text" name="cep" class="form-control" oninput="mascara(this, 'cep')"></div>
                    <div class="col-md-7"><label class="form-label">Logradouro</label><input type="text" name="logradouro" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label">Nº</label><input type="text" name="numero" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Bairro</label><input type="text" name="bairro" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Cidade</label><input type="text" name="cidade" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label">UF</label><input type="text" name="estado" class="form-control" maxlength="2"></div>
                    <div class="col-md-12"><label class="form-label">Ponto de Referência</label><input type="text" name="ponto_referencia" class="form-control"></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Acesso e Contato</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">E-mail de Login</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required-label">Celular</label>
                        <input type="text" name="celular" class="form-control" oninput="mascara(this, 'tel')" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Telefone Fixo</label>
                        <input type="text" name="telefone" class="form-control" oninput="mascara(this, 'tel')">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label required-label">Nível de Permissão</label>
                        <select name="permissao_id" class="form-select" required>
                            <option value="2">Cliente</option>
                            <option value="3">Gestor</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <button type="submit" name="submit" class="btn-custom mb-5 shadow">Finalizar Cadastro</button>
        </form>
    </div>

    <script>
        function toggleTipo(tipo) {
            const pf = document.querySelectorAll('.area_pf');
            const pj = document.querySelectorAll('.area_pj');
            const inputsPf = document.querySelectorAll('.req-pf');
            const inputsPj = document.querySelectorAll('.req-pj');

            if(tipo === 'PF'){
                pf.forEach(el => el.classList.remove('hidden'));
                pj.forEach(el => el.classList.add('hidden'));
                inputsPf.forEach(el => el.required = true);
                inputsPj.forEach(el => el.required = false);
            } else {
                pf.forEach(el => el.classList.add('hidden'));
                pj.forEach(el => el.classList.remove('hidden'));
                inputsPf.forEach(el => el.required = false);
                inputsPj.forEach(el => el.required = true);
            }
        }
        
        window.onload = () => toggleTipo('PF');

        function mascara(i, t) {
            let v = i.value.replace(/\D/g, "");
            if (t == 'cpf') v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            if (t == 'cnpj') v = v.replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2");
            if (t == 'tel') v = v.replace(/^(\d{2})(\d)/g, "($1) $2").replace(/(\d)(\d{4})$/, "$1-$2");
            if (t == 'cep') v = v.replace(/^(\d{5})(\d)/, "$1-$2");
            i.value = v;
        }

        const btnTema = document.getElementById('btn-tema');
        const html = document.documentElement;
        const setTema = (tema) => {
            html.setAttribute('data-bs-theme', tema);
            localStorage.setItem('tema', tema);
            btnTema.innerHTML = tema === 'dark' ? '<i class=\"bi bi-moon-stars-fill\"></i> TEMA' : '<i class=\"bi bi-brightness-high-fill\"></i> TEMA';
        };
        setTema(localStorage.getItem('tema') || 'dark');
        btnTema.addEventListener('click', () => {
            const novo = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTema(novo);
        });
    </script>
</body>
</html>