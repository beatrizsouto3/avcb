<?php
$sucesso = false;
$erroEmail = false;

$nome = $nome_fantasia = $data_referencia = $cpf_cnpj = $rg = $inscricao_estadual = "";
$representante_nome = $representante_cpf = $representante_cargo = "";
$cep = $logradouro = $numero = $complemento = $bairro = $cidade = $estado = "";
$telefone = $celular = $email = "";
$perfil_cliente = $origem_contato = $observacoes = "";

if (isset($_POST['submit'])) {
    include_once('config.php');

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
    $lgpd = isset($_POST['lgpd']) ? 'true' : 'false';

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
            perfil_cliente, origem_contato, observacoes, lgpd,
            permissao_id, primeiro_acesso
        ) VALUES (
            :nome, :nome_fantasia, :data_referencia, :tipo_cliente, :cpf_cnpj, :rg, :inscricao_estadual,
            :representante_nome, :representante_cpf, :representante_cargo,
            :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado,
            :telefone, :celular, :email, :senha,
            :perfil_cliente, :origem_contato, :observacoes, :lgpd,
            2, 'true'
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
                ':observacoes' => $observacoes, ':lgpd' => $lgpd
            ]);
            
            include_once('enviarEmail.php');
            enviarEmailBoasVindas($nome, $email, $senhaLimpa);
            $sucesso = true;

        } catch (PDOException $e) {
            echo "<script>alert('Erro no banco: " . $e->getMessage() . "');</script>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Registro</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(80, 220, 120), rgb(20, 70, 35));
            min-height: 100vh;
            padding: 20px;
            color: white;
            display: flex;
            align-items: center; 
            justify-content: center;
        }
        .container-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            max-width: 900px;
            width: 100%;
            margin: auto;
        }
        fieldset { border: 1px solid limegreen; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        legend { float: none; width: auto; background-color: limegreen; padding: 5px 15px; border-radius: 5px; color: black; font-weight: bold; font-size: 1rem; }
        label { font-weight: bold; margin-bottom: 5px; display: block; font-size: 0.9rem; }
        .form-control, .form-select { background: rgba(255,255,255,0.9); border: none; margin-bottom: 15px; }
        .btn-custom { background-image: linear-gradient(to right, rgb(50, 205, 50), rgb(34, 139, 34)); width: 100%; border: none; padding: 15px; color: white; font-size: 16px; cursor: pointer; border-radius: 10px; font-weight: bold; margin-top: 10px; }
        .hidden { display: none; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-half { flex: 1 1 45%; }
        .col-full { flex: 1 1 100%; }
        .msg-erro { background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; text-align: center; }
        .sucesso-box { text-align: center; padding: 50px; }

        .aviso-container { text-align: center; padding: 20px; }
        
        .contato-destaque {
            font-size: 1.5rem; 
            color: limegreen; 
            font-weight: bold; 
            margin: 20px 0;
            border: 2px dashed limegreen; 
            padding: 15px; 
            border-radius: 10px; 
            display: inline-block;
            transition: 0.3s;
        }
        .contato-destaque:hover {
            background-color: rgba(50, 205, 50, 0.1);
            cursor: pointer;
        }
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
            if (t == 'cpf') { v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2"); i.setAttribute("maxlength", "14"); }
            else if (t == 'cnpj') { v = v.replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2"); i.setAttribute("maxlength", "18"); }
            else if (t == 'tel') { i.setAttribute("maxlength", "15"); v = v.replace(/^(\d{2})(\d)/g, "($1) $2").replace(/(\d)(\d{4})$/, "$1-$2"); }
            else if (t == 'cep') { v = v.replace(/^(\d{5})(\d)/, "$1-$2"); i.setAttribute("maxlength", "9"); }
            i.value = v;
        }
    </script>
</head>
<body>

<div class="container-box">
    
    <div class="aviso-container">
        <h2 class="mb-4">Registro no Sistema</h2>
        <p class="lead">Para se registrar no nosso sistema, por favor, entre em contato conosco:</p>
        
        <div class="contato-destaque">
            <a href="https://wa.me/5584999999999?text=Olá,%20gostaria%20de%20me%20registrar%20no%20sistema." 
               target="_blank" 
               style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-whatsapp"></i> (84) 99999-9999
            </a>
        </div>
        
        <br><br>
        <a href="inicio.php" style="color:white; text-decoration:none; border:1px solid white; padding:10px 20px; border-radius:5px;">Voltar ao Início</a>
    </div>

</body>
</html>