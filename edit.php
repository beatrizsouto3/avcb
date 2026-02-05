<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1)){
        header('Location: sistema.php');
        exit;
    }

    if(!empty($_GET['id']))
    {
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nome = $user_data['nome'];
            $email = $user_data['email'];
            $telefone = $user_data['telefone'];
            $permissao_id = $user_data['permissao_id'];
            
            $tipo_cliente = $user_data['tipo_cliente'];
            $nome_fantasia = $user_data['nome_fantasia'];
            $data_referencia = $user_data['data_nascimento_fundacao'];
            $cpf_cnpj = $user_data['cpf_cnpj'];
            $rg = $user_data['rg'];
            $inscricao_estadual = $user_data['inscricao_estadual'];
            
            $representante_nome = $user_data['representante_nome'];
            $representante_cpf = $user_data['representante_cpf'];
            $representante_cargo = $user_data['representante_cargo'];
            
            $cep = $user_data['cep'];
            $logradouro = $user_data['logradouro'];
            $numero = $user_data['numero'];
            $complemento = $user_data['complemento'];
            $bairro = $user_data['bairro'];
            $cidade = $user_data['cidade'];
            $estado = $user_data['estado'];
            
            $celular = $user_data['celular'];
            $perfil_cliente = $user_data['perfil_cliente'];
            $origem_contato = $user_data['origem_contato'];
            $observacoes = $user_data['observacoes'];
        }
        else {
            header('Location: sistema.php?page=usuarios');
        }
    }
    else {
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
        
        window.onload = function() {
            toggleTipoCliente('<?php echo $tipo_cliente; ?>');
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
                <label style="cursor:pointer;">
                    <input type="radio" name="tipo_cliente" value="PF" <?php echo ($tipo_cliente == 'PF') ? 'checked' : '' ?> onclick="toggleTipoCliente('PF')"> Pessoa Física
                </label>
                <label style="cursor:pointer;">
                    <input type="radio" name="tipo_cliente" value="PJ" <?php echo ($tipo_cliente == 'PJ') ? 'checked' : '' ?> onclick="toggleTipoCliente('PJ')"> Pessoa Jurídica
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dados Gerais</legend>
            <div class="row">
                <div class="col-half">
                    <label>Nome / Razão Social *</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo $nome;?>" required>
                </div>
                <div class="col-half">
                    <label>Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" class="form-control" value="<?php echo $nome_fantasia;?>">
                </div>
                <div class="col-half">
                    <label>Data Nascimento / Fundação</label>
                    <input type="date" name="data_referencia" class="form-control" value="<?php echo $data_referencia;?>">
                </div>
            </div>

            <div id="area_pf">
                <div class="row">
                    <div class="col-half"><label>CPF *</label><input type="text" name="cpf" id="cpf" class="form-control" oninput="mascara(this, 'cpf')" value="<?php echo ($tipo_cliente == 'PF') ? $cpf_cnpj : ''; ?>"></div>
                    <div class="col-half"><label>RG</label><input type="text" name="rg" class="form-control" value="<?php echo $rg;?>"></div>
                </div>
            </div>

            <div id="area_pj" class="hidden">
                <div class="row">
                    <div class="col-half"><label>CNPJ *</label><input type="text" name="cnpj" id="cnpj" class="form-control" oninput="mascara(this, 'cnpj')" value="<?php echo ($tipo_cliente == 'PJ') ? $cpf_cnpj : ''; ?>"></div>
                    <div class="col-half"><label>Inscrição Estadual</label><input type="text" name="inscricao_estadual" class="form-control" value="<?php echo $inscricao_estadual;?>"></div>
                </div>
                <h4 style="margin-top:15px; color:limegreen; font-size:1rem;">Representante Legal</h4>
                <div class="row">
                    <div class="col-half"><label>Nome</label><input type="text" name="representante_nome" class="form-control" value="<?php echo $representante_nome;?>"></div>
                    <div class="col-half"><label>CPF</label><input type="text" name="representante_cpf" class="form-control" oninput="mascara(this, 'cpf')" value="<?php echo $representante_cpf;?>"></div>
                    <div class="col-half"><label>Cargo</label><input type="text" name="representante_cargo" class="form-control" value="<?php echo $representante_cargo;?>"></div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Endereço</legend>
            <div class="row">
                <div class="col-half" style="flex: 0 0 30%;"><label>CEP</label><input type="text" name="cep" class="form-control" oninput="mascara(this, 'cep')" value="<?php echo $cep;?>"></div>
                <div class="col-half" style="flex: 1;"><label>Logradouro</label><input type="text" name="logradouro" class="form-control" value="<?php echo $logradouro;?>"></div>
            </div>
            <div class="row">
                <div class="col-half"><label>Número</label><input type="text" name="numero" class="form-control" value="<?php echo $numero;?>"></div>
                <div class="col-half"><label>Complemento</label><input type="text" name="complemento" class="form-control" value="<?php echo $complemento;?>"></div>
                <div class="col-half"><label>Bairro</label><input type="text" name="bairro" class="form-control" value="<?php echo $bairro;?>"></div>
            </div>
            <div class="row">
                <div class="col-half"><label>Cidade</label><input type="text" name="cidade" class="form-control" value="<?php echo $cidade;?>"></div>
                <div class="col-half" style="flex: 0 0 20%;"><label>UF</label><input type="text" name="estado" class="form-control" maxlength="2" value="<?php echo $estado;?>"></div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Acesso e Contato</legend>
            <div class="row">
                <div class="col-half"><label>E-mail *</label><input type="email" name="email" class="form-control" value="<?php echo $email;?>" required></div>
                <div class="col-half"><label>Senha (Deixe vazio para não mudar)</label><input type="password" name="senha" class="form-control" placeholder="Nova Senha?"></div>
                
                <div class="col-half">
                    <label>Celular / Zap *</label>
                    <input type="text" name="celular" class="form-control" oninput="mascara(this, 'tel')" value="<?php echo $celular;?>" required>
                </div>
                <div class="col-half">
                    <label>Telefone Fixo</label>
                    <input type="text" name="telefone" class="form-control" oninput="mascara(this, 'tel')" value="<?php echo $telefone;?>">
                </div>
            </div>
            <div class="row">
                <div class="col-full">
                    <label>Nível de Acesso</label>
                    <select name="permissao_id" class="form-select">
                        <option value="2" <?php echo ($permissao_id == 2) ? 'selected' : ''; ?>>Cliente</option>
                        <option value="3" <?php echo ($permissao_id == 3) ? 'selected' : ''; ?>>Gestor</option>
                        <option value="1" <?php echo ($permissao_id == 1) ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Info. Comerciais</legend>
            <div class="row">
                <div class="col-half">
                    <label>Perfil</label>
                    <select name="perfil_cliente" class="form-select">
                        <option value="">Selecione...</option>
                        <option <?php echo ($perfil_cliente == 'Residencial') ? 'selected' : ''; ?>>Residencial</option>
                        <option <?php echo ($perfil_cliente == 'Comercial') ? 'selected' : ''; ?>>Comercial</option>
                        <option <?php echo ($perfil_cliente == 'Industrial') ? 'selected' : ''; ?>>Industrial</option>
                    </select>
                </div>
                <div class="col-half">
                    <label>Origem</label>
                    <select name="origem_contato" class="form-select">
                        <option value="">Selecione...</option>
                        <option <?php echo ($origem_contato == 'Indicação') ? 'selected' : ''; ?>>Indicação</option>
                        <option <?php echo ($origem_contato == 'Internet') ? 'selected' : ''; ?>>Internet</option>
                        <option <?php echo ($origem_contato == 'Telefone') ? 'selected' : ''; ?>>Telefone</option>
                        <option <?php echo ($origem_contato == 'Outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>
                <div class="col-full">
                    <label>Observações</label>
                    <textarea name="observacoes" class="form-control"><?php echo $observacoes;?></textarea>
                </div>
            </div>
        </fieldset>

        <input type="hidden" name="id" value="<?php echo $id;?>">
        <button type="submit" name="update" class="btn-custom">Salvar Alterações</button>
    </form>
</div>
</body>
</html>