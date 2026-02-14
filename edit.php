<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id'])) {
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
        $result = $pdo->query($sqlSelect);

        if($result->rowCount() > 0) {
            $user_data = $result->fetch(PDO::FETCH_ASSOC);
            
            $nome = $user_data['nome'];
            $nome_fantasia = $user_data['nome_fantasia'];
            $tipo_cliente = $user_data['tipo_cliente'];
            $data_referencia = $user_data['data_nascimento_fundacao'];
            $cpf_cnpj = $user_data['cpf_cnpj'];
            $rg = $user_data['rg'];
            $inscricao_estadual = $user_data['inscricao_estadual'];
            $representante_nome = $user_data['representante_nome'];
            $representante_cpf = $user_data['representante_cpf'];
            $representante_cargo = $user_data['representante_cargo'];
            $representante_email = $user_data['representante_email'];
            $cep = $user_data['cep'];
            $logradouro = $user_data['logradouro'];
            $numero = $user_data['numero'];
            $complemento = $user_data['complemento'];
            $bairro = $user_data['bairro'];
            $cidade = $user_data['cidade'];
            $estado = $user_data['estado'];
            $telefone = $user_data['telefone'];
            $celular = $user_data['celular'];
            $email = $user_data['email'];
            $perfil_cliente = $user_data['perfil_cliente'];
            $origem_contato = $user_data['origem_contato'];
            $observacoes = $user_data['observacoes'];
            $permissao_id = $user_data['permissao_id'];
        } else {
            header('Location: sistema.php?page=usuarios');
            exit;
        }
    } else {
        header('Location: sistema.php?page=usuarios');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Editar Usuário | AVCB</title>
    <style>
        body { background-color: var(--bs-body-bg); padding: 40px 0; transition: all 0.3s ease; }
        .container { max-width: 900px; }
        fieldset { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; }
        legend { float: none; width: auto; padding: 0 15px; font-size: 1rem; font-weight: 700; text-transform: uppercase; color: var(--bs-emphasis-color); background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 6px; margin-bottom: 20px; }
        .form-label { font-size: 0.8rem; text-transform: uppercase; opacity: 0.7; font-weight: 600; }
        .required-label::after { content: " *"; color: #dc3545; font-weight: bold; }
        .btn-update { background-color: var(--bs-emphasis-color); color: var(--bs-body-bg); border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; text-transform: uppercase; }
        .hidden { display: none; }
        .btn-voltar { color: var(--bs-emphasis-color); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="sistema.php?page=usuarios" class="btn-voltar"><i class="bi bi-arrow-left"></i> VOLTAR</a>
                <h2 class="fw-bold mt-2">EDITAR REGISTRO</h2>
            </div>
            <button class="btn btn-outline-secondary btn-sm" id="btn-tema">
                <i class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <form action="saveEdit.php" method="POST">
            <fieldset>
                <legend>Tipo de Perfil</legend>
                <div class="d-flex gap-4 justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PF" id="pf" <?php echo ($tipo_cliente == 'PF') ? 'checked' : ''; ?> onclick="toggleTipo('PF')">
                        <label class="form-check-label fw-bold" for="pf">Pessoa Física</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_cliente" value="PJ" id="pj" <?php echo ($tipo_cliente == 'PJ') ? 'checked' : ''; ?> onclick="toggleTipo('PJ')">
                        <label class="form-check-label fw-bold" for="pj">Pessoa Jurídica</label>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Dados Gerais</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">Nome / Razão Social</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" class="form-control" value="<?php echo $nome_fantasia; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Nasc. / Fundação</label>
                        <input type="date" name="data_referencia" class="form-control" value="<?php echo $data_referencia; ?>">
                    </div>
                    
                    <div class="col-md-4 area_pf <?php echo ($tipo_cliente == 'PJ') ? 'hidden' : ''; ?>">
                        <label class="form-label required-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control req-pf" value="<?php echo ($tipo_cliente == 'PF') ? $cpf_cnpj : ''; ?>" oninput="mascara(this, 'cpf')">
                    </div>
                    <div class="col-md-4 area_pf <?php echo ($tipo_cliente == 'PJ') ? 'hidden' : ''; ?>">
                        <label class="form-label">RG</label>
                        <input type="text" name="rg" class="form-control" value="<?php echo $rg; ?>">
                    </div>

                    <div class="col-md-4 area_pj <?php echo ($tipo_cliente == 'PF') ? 'hidden' : ''; ?>">
                        <label class="form-label required-label">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control req-pj" value="<?php echo ($tipo_cliente == 'PJ') ? $cpf_cnpj : ''; ?>" oninput="mascara(this, 'cnpj')">
                    </div>
                    <div class="col-md-4 area_pj <?php echo ($tipo_cliente == 'PF') ? 'hidden' : ''; ?>">
                        <label class="form-label">Insc. Estadual</label>
                        <input type="text" name="inscricao_estadual" class="form-control" value="<?php echo $inscricao_estadual; ?>">
                    </div>
                </div>

                <div class="area_pj <?php echo ($tipo_cliente == 'PF') ? 'hidden' : ''; ?> mt-4 border-top pt-4">
                    <h6 class="fw-bold mb-3 text-uppercase small opacity-75">Representante Legal</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label required-label">Nome</label><input type="text" name="representante_nome" class="form-control req-pj" value="<?php echo $representante_nome; ?>"></div>
                        <div class="col-md-6"><label class="form-label required-label">CPF</label><input type="text" name="representante_cpf" class="form-control req-pj" value="<?php echo $representante_cpf; ?>" oninput="mascara(this, 'cpf')"></div>
                        <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="representante_cargo" class="form-control" value="<?php echo $representante_cargo; ?>"></div>
                        <div class="col-md-6"><label class="form-label">E-mail</label><input type="email" name="representante_email" class="form-control" value="<?php echo $representante_email; ?>"></div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Endereço (Opcional)</legend>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">CEP</label><input type="text" name="cep" class="form-control" value="<?php echo $cep; ?>" oninput="mascara(this, 'cep')"></div>
                    <div class="col-md-6"><label class="form-label">Logradouro</label><input type="text" name="logradouro" class="form-control" value="<?php echo $logradouro; ?>"></div>
                    <div class="col-md-3"><label class="form-label">Número</label><input type="text" name="numero" class="form-control" value="<?php echo $numero; ?>"></div>
                    <div class="col-md-4"><label class="form-label">Bairro</label><input type="text" name="bairro" class="form-control" value="<?php echo $bairro; ?>"></div>
                    <div class="col-md-4"><label class="form-label">Cidade</label><input type="text" name="cidade" class="form-control" value="<?php echo $cidade; ?>"></div>
                    <div class="col-md-2"><label class="form-label">UF</label><input type="text" name="estado" class="form-control" value="<?php echo $estado; ?>" maxlength="2"></div>
                    <div class="col-md-12"><label class="form-label">Complemento</label><input type="text" name="complemento" class="form-control" value="<?php echo $complemento; ?>"></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Segurança e Acesso</legend>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label required-label">E-mail (Login)</label><input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required></div>
                    <div class="col-md-3"><label class="form-label required-label">Celular</label><input type="text" name="celular" class="form-control req-always" value="<?php echo $celular; ?>" oninput="mascara(this, 'tel')"></div>
                    <div class="col-md-3"><label class="form-label">Telefone</label><input type="text" name="telefone" class="form-control" value="<?php echo $telefone; ?>" oninput="mascara(this, 'tel')"></div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Nova Senha (Em branco para não alterar)</label>
                        <input type="password" name="senha" class="form-control" placeholder="********">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Nível de Permissão</label>
                        <select name="permissao_id" class="form-select" <?php echo ($_SESSION['permissao'] != 1) ? 'disabled' : ''; ?> required>
                            <option value="2" <?php echo ($permissao_id == 2) ? 'selected' : ''; ?>>Cliente</option>
                            <option value="3" <?php echo ($permissao_id == 3) ? 'selected' : ''; ?>>Gestor</option>
                            <option value="1" <?php echo ($permissao_id == 1) ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                        <?php if($_SESSION['permissao'] != 1): ?>
                            <input type="hidden" name="permissao_id" value="<?php echo $permissao_id; ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>

            <fieldset id="area_comercial" class="<?php echo ($tipo_cliente == 'PF') ? 'hidden' : ''; ?>">
                <legend>Info. Comerciais</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Perfil</label>
                        <select name="perfil_cliente" class="form-select">
                            <option value="" <?php echo empty($perfil_cliente) ? 'selected' : ''; ?>>Selecione...</option>
                            <option value="Residencial" <?php echo ($perfil_cliente == 'Residencial') ? 'selected' : ''; ?>>Residencial</option>
                            <option value="Comercial" <?php echo ($perfil_cliente == 'Comercial') ? 'selected' : ''; ?>>Comercial</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Origem</label>
                        <select name="origem_contato" class="form-select">
                            <option value="" <?php echo empty($origem_contato) ? 'selected' : ''; ?>>Selecione...</option>
                            <option value="Indicação" <?php echo ($origem_contato == 'Indicação') ? 'selected' : ''; ?>>Indicação</option>
                            <option value="Internet" <?php echo ($origem_contato == 'Internet') ? 'selected' : ''; ?>>Internet</option>
                        </select>
                    </div>
                    <div class="col-md-12"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control" rows="3"><?php echo $observacoes; ?></textarea></div>
                </div>
            </fieldset>

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" name="update" class="btn-update mb-5">Salvar Alterações</button>
        </form>
    </div>

    <script>
        function toggleTipo(tipo) {
            const pf = document.querySelectorAll('.area_pf');
            const pj = document.querySelectorAll('.area_pj');
            const comercial = document.getElementById('area_comercial');
            const inputsPf = document.querySelectorAll('.req-pf');
            const inputsPj = document.querySelectorAll('.req-pj');
            
            if(tipo === 'PF'){
                pf.forEach(el => el.classList.remove('hidden'));
                pj.forEach(el => el.classList.add('hidden'));
                comercial.classList.add('hidden');
                inputsPf.forEach(el => el.required = true);
                inputsPj.forEach(el => el.required = false);
            } else {
                pf.forEach(el => el.classList.add('hidden'));
                pj.forEach(el => el.classList.remove('hidden'));
                comercial.classList.remove('hidden');
                inputsPf.forEach(el => el.required = false);
                inputsPj.forEach(el => el.required = true);
            }
        }

        window.onload = () => {
            const tipoAtual = document.querySelector('input[name="tipo_cliente"]:checked').value;
            toggleTipo(tipoAtual);
            document.querySelectorAll('.req-always').forEach(el => el.required = true);
        };

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
            btnTema.innerHTML = tema === 'dark' ? '<i class="bi bi-moon-stars-fill"></i>' : '<i class="bi bi-brightness-high-fill"></i>';
        };
        setTema(localStorage.getItem('tema') || 'dark');
        btnTema.addEventListener('click', () => {
            const novo = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTema(novo);
        });
    </script>
</body>
</html>