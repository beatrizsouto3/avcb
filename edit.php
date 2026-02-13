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
            $ponto_referencia = $user_data['ponto_referencia'];

            $telefone = $user_data['telefone'];
            $celular = $user_data['celular'];
            $email = $user_data['email'];
            $permissao_atual = $user_data['permissao_id'];

            $perfil_cliente = $user_data['perfil_cliente'];
            $origem_contato = $user_data['origem_contato'];
            $observacoes = $user_data['observacoes'];

        } else {
            header('Location: sistema.php?page=usuarios');
        }
    } else {
        header('Location: sistema.php?page=usuarios');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Usuário</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            min-height: 100vh; padding: 20px; color: white;
        }
        .container-box {
            background-color: rgba(0, 0, 0, 0.7); padding: 30px; border-radius: 15px; max-width: 900px; margin: auto;
        }
        fieldset { border: 1px solid limegreen; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        legend { 
            float: none; width: auto; background-color: limegreen; 
            padding: 5px 15px; border-radius: 5px; color: black; font-weight: bold; font-size: 1rem;
        }
        label { font-weight: bold; margin-bottom: 5px; display: block; font-size: 0.9rem; }
        .form-control, .form-select { background: rgba(255,255,255,0.9); border: none; margin-bottom: 15px; }
        .btn-custom {
            background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34));
            width: 100%; border: none; padding: 15px; color: white; font-size: 16px;
            cursor: pointer; border-radius: 10px; font-weight: bold; margin-top: 10px;
        }
        .hidden { display: none; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-half { flex: 1 1 45%; }
        .col-full { flex: 1 1 100%; }
    </style>
    
    <script>
        function toggleTipoCliente(tipo) {
            document.getElementById('area_pf').classList.add('hidden');
            document.getElementById('area_pj').classList.add('hidden');
            document.getElementById('area_comercial').classList.add('hidden');

            var inputCPF = document.getElementById('cpf');
            var inputCNPJ = document.getElementById('cnpj');
            var inputRepCPF = document.getElementById('representante_cpf');
            var inputRepEmail = document.getElementById('representante_email');

            if(tipo === 'PF'){
                document.getElementById('area_pf').classList.remove('hidden');
                
                inputCPF.required = true;
                inputCNPJ.required = false;
                if(inputRepCPF) inputRepCPF.required = false;
                if(inputRepEmail) inputRepEmail.required = false;

            } else {
                document.getElementById('area_pj').classList.remove('hidden');
                document.getElementById('area_comercial').classList.remove('hidden');

                inputCPF.required = false;
                inputCNPJ.required = true;
                if(inputRepCPF) inputRepCPF.required = true;
                if(inputRepEmail) inputRepEmail.required = true;
            }
        }
        
        function mascara(i, t) {
            var v = i.value; v = v.replace(/\D/g, "");
            if (t == 'cpf') { v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2"); i.setAttribute("maxlength", "14"); }
            else if (t == 'cnpj') { v = v.replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2"); i.setAttribute("maxlength", "18"); }
            else if (t == 'tel') { i.setAttribute("maxlength", "15"); v = v.replace(/^(\d{2})(\d)/g, "($1) $2").replace(/(\d)(\d{4})$/, "$1-$2"); }
            else if (t == 'cep') { v = v.replace(/^(\d{5})(\d)/, "$1-$2"); i.setAttribute("maxlength", "9"); }
            i.value = v;
        }

        window.onload = function() {
            var tipo = "<?php echo $tipo_cliente; ?>";
            toggleTipoCliente(tipo);
        }
    </script>
</head>
<body>

<div class="container-box">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Editar Usuário</h2>
        <a href="sistema.php?page=usuarios" style="color:white; text-decoration:none; border:1px solid white; padding:5px 10px; border-radius:5px;">Voltar</a>
    </div>
    <hr>

    <form action="saveEdit.php" method="POST">
        <fieldset>
            <legend>Tipo de Cliente</legend>
            <div style="display:flex; gap:20px; justify-content:center;">
                <label style="cursor:pointer;"><input type="radio" name="tipo_cliente" value="PF" <?php echo ($tipo_cliente == 'PF') ? 'checked' : ''; ?> onclick="toggleTipoCliente('PF')"> Pessoa Física</label>
                <label style="cursor:pointer;"><input type="radio" name="tipo_cliente" value="PJ" <?php echo ($tipo_cliente == 'PJ') ? 'checked' : ''; ?> onclick="toggleTipoCliente('PJ')"> Pessoa Jurídica</label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dados Gerais</legend>
            <div class="row">
                <div class="col-half"><label>Nome / Razão Social *</label><input type="text" name="nome" value="<?php echo $nome;?>" class="form-control" required></div>
                <div class="col-half"><label>Nome Fantasia</label><input type="text" name="nome_fantasia" value="<?php echo $nome_fantasia;?>" class="form-control"></div>
                <div class="col-half"><label>Data Nascimento / Fundação</label><input type="date" name="data_referencia" value="<?php echo $data_referencia;?>" class="form-control"></div>
            </div>
            
            <div id="area_pf">
                <div class="row">
                    <div class="col-half"><label>CPF *</label><input type="text" name="cpf" id="cpf" value="<?php echo ($tipo_cliente == 'PF') ? $cpf_cnpj : '';?>" class="form-control" oninput="mascara(this, 'cpf')"></div>
                    <div class="col-half"><label>RG</label><input type="text" name="rg" value="<?php echo $rg;?>" class="form-control"></div>
                </div>
            </div>
            
            <div id="area_pj" class="hidden">
                <div class="row">
                    <div class="col-half"><label>CNPJ *</label><input type="text" name="cnpj" id="cnpj" value="<?php echo ($tipo_cliente == 'PJ') ? $cpf_cnpj : '';?>" class="form-control" oninput="mascara(this, 'cnpj')"></div>
                    <div class="col-half"><label>Inscrição Estadual</label><input type="text" name="inscricao_estadual" value="<?php echo $inscricao_estadual;?>" class="form-control"></div>
                </div>
                <h4 style="margin-top:15px; color:limegreen; font-size:1rem;">Representante Legal</h4>
                <div class="row">
                    <div class="col-half"><label>Nome</label><input type="text" name="representante_nome" value="<?php echo $representante_nome;?>" class="form-control"></div>
                    <div class="col-half"><label>CPF *</label><input type="text" name="representante_cpf" id="representante_cpf" value="<?php echo $representante_cpf;?>" class="form-control" oninput="mascara(this, 'cpf')"></div>
                    <div class="col-half"><label>Cargo</label><input type="text" name="representante_cargo" value="<?php echo $representante_cargo;?>" class="form-control"></div>
                    <div class="col-half"><label>E-mail *</label><input type="email" name="representante_email" id="representante_email" value="<?php echo $representante_email;?>" class="form-control"></div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Endereço</legend>
            <div class="row">
                <div class="col-half"><label>CEP</label><input type="text" name="cep" value="<?php echo $cep;?>" class="form-control" oninput="mascara(this, 'cep')"></div>
                <div class="col-half"><label>Logradouro</label><input type="text" name="logradouro" value="<?php echo $logradouro;?>" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-half"><label>Número</label><input type="text" name="numero" value="<?php echo $numero;?>" class="form-control"></div>
                <div class="col-half"><label>Bairro</label><input type="text" name="bairro" value="<?php echo $bairro;?>" class="form-control"></div>
                <div class="col-half"><label>Cidade</label><input type="text" name="cidade" value="<?php echo $cidade;?>" class="form-control"></div>
                <div class="col-half"><label>UF</label><input type="text" name="estado" value="<?php echo $estado;?>" class="form-control" maxlength="2"></div>
                <div class="col-half"><label>Complemento</label><input type="text" name="complemento" value="<?php echo $complemento;?>" class="form-control"></div>
                <div class="col-half"><label>Ponto de Referência</label><input type="text" name="ponto_referencia" value="<?php echo $ponto_referencia;?>" class="form-control"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Acesso e Contato</legend>
            <div class="row">
                <div class="col-half"><label>E-mail (Login) *</label><input type="email" name="email" value="<?php echo $email;?>" class="form-control" required></div>
                <div class="col-half"><label>Celular / WhatsApp *</label><input type="text" name="celular" value="<?php echo $celular;?>" class="form-control" oninput="mascara(this, 'tel')" required></div>
                <div class="col-half"><label>Telefone Fixo</label><input type="text" name="telefone" value="<?php echo $telefone;?>" class="form-control" oninput="mascara(this, 'tel')"></div>
            </div>
            
            <div class="row">
                <div class="col-full">
                    <label>Nível de Acesso</label>
                    <?php if($_SESSION['permissao'] == 1): ?>
                        <select name="permissao_id" class="form-select">
                            <option value="2" <?php echo ($permissao_atual == 2) ? 'selected' : ''; ?>>Cliente</option>
                            <option value="3" <?php echo ($permissao_atual == 3) ? 'selected' : ''; ?>>Gestor</option>
                            <option value="1" <?php echo ($permissao_atual == 1) ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    <?php else: ?>
                        <input type="text" class="form-control" value="Cliente" disabled style="background: #ccc; color: #333;">
                        <input type="hidden" name="permissao_id" value="2">
                    <?php endif; ?>
                </div>
            </div>
        </fieldset>

        <fieldset id="area_comercial" class="hidden">
            <legend>Info. Comerciais</legend>
            <div class="row">
                <div class="col-half">
                    <label>Perfil</label>
                    <select name="perfil_cliente" class="form-select">
                        <option <?php echo ($perfil_cliente == 'Residencial') ? 'selected' : ''; ?>>Residencial</option>
                        <option <?php echo ($perfil_cliente == 'Comercial') ? 'selected' : ''; ?>>Comercial</option>
                    </select>
                </div>
                <div class="col-half">
                    <label>Origem</label>
                    <select name="origem_contato" class="form-select">
                        <option <?php echo ($origem_contato == 'Indicação') ? 'selected' : ''; ?>>Indicação</option>
                        <option <?php echo ($origem_contato == 'Internet') ? 'selected' : ''; ?>>Internet</option>
                    </select>
                </div>
                <div class="col-full"><label>Observações</label><textarea name="observacoes" class="form-control"><?php echo $observacoes;?></textarea></div>
            </div>
        </fieldset>

        <input type="hidden" name="id" value="<?php echo $id;?>">
        <button type="submit" name="update" class="btn-custom">Salvar Alterações</button>
    </form>
</div>
</body>
</html>