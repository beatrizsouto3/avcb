<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or ($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        
        if($_SESSION['permissao'] == 3){
            $sqlCheck = "SELECT permissao_id FROM usuarios WHERE id = :id";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id]);
            $alvo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if($alvo['permissao_id'] != 2){
                header('Location: sistema.php?msg=sem_permissao');
                exit;
            }
        }

        $nome = $_POST['nome'];
        $nome_fantasia = $_POST['nome_fantasia'];
        $email = $_POST['email'];
        $data_referencia = !empty($_POST['data_referencia']) ? $_POST['data_referencia'] : null;

        $senhaPost = $_POST['senha'];
        $senhaUpdate = "";
        if(!empty($senhaPost)){
            $senhaMd5 = md5($senhaPost);
            $senhaUpdate = ", senha = '$senhaMd5'"; 
        }

        $tipo_cliente = $_POST['tipo_cliente'];
        $cpf_cnpj = ($tipo_cliente == 'PJ') ? $_POST['cnpj'] : $_POST['cpf'];
        
        $rg = $_POST['rg'];
        $inscricao_estadual = $_POST['inscricao_estadual'];
        
        $representante_nome = $_POST['representante_nome'];
        $representante_cpf = $_POST['representante_cpf'];
        $representante_cargo = $_POST['representante_cargo'];

        $cep = $_POST['cep']; $logradouro = $_POST['logradouro']; $numero = $_POST['numero'];
        $complemento = $_POST['complemento']; $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade']; $estado = $_POST['estado'];

        $telefone = $_POST['telefone']; $celular = $_POST['celular'];
        $perfil_cliente = $_POST['perfil_cliente']; $origem_contato = $_POST['origem_contato'];
        $observacoes = $_POST['observacoes'];
        
        if($_SESSION['permissao'] == 1){
            $permissao_id = $_POST['permissao_id'];
        } else {
            $permissao_id = 2;
        }

        $sql = "UPDATE usuarios SET 
                    nome = :nome, 
                    nome_fantasia = :nome_fantasia,
                    email = :email,
                    data_nascimento_fundacao = :data_referencia,
                    tipo_cliente = :tipo_cliente,
                    cpf_cnpj = :cpf_cnpj,
                    rg = :rg,
                    inscricao_estadual = :inscricao_estadual,
                    representante_nome = :representante_nome,
                    representante_cpf = :representante_cpf,
                    representante_cargo = :representante_cargo,
                    cep = :cep,
                    logradouro = :logradouro,
                    numero = :numero,
                    complemento = :complemento,
                    bairro = :bairro,
                    cidade = :cidade,
                    estado = :estado,
                    telefone = :telefone,
                    celular = :celular,
                    perfil_cliente = :perfil_cliente,
                    origem_contato = :origem_contato,
                    observacoes = :observacoes,
                    permissao_id = :permissao_id
                    $senhaUpdate
                WHERE id = :id";
        
        try {
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nome' => $nome, ':nome_fantasia' => $nome_fantasia, ':email' => $email,
                ':data_referencia' => $data_referencia, ':tipo_cliente' => $tipo_cliente,
                ':cpf_cnpj' => $cpf_cnpj, ':rg' => $rg, ':inscricao_estadual' => $inscricao_estadual,
                ':representante_nome' => $representante_nome, ':representante_cpf' => $representante_cpf, ':representante_cargo' => $representante_cargo,
                ':cep' => $cep, ':logradouro' => $logradouro, ':numero' => $numero, ':complemento' => $complemento,
                ':bairro' => $bairro, ':cidade' => $cidade, ':estado' => $estado,
                ':telefone' => $telefone, ':celular' => $celular, 
                ':perfil_cliente' => $perfil_cliente, ':origem_contato' => $origem_contato,
                ':observacoes' => $observacoes, ':permissao_id' => $permissao_id,
                ':id' => $id
            ]);

            header('Location: sistema.php?page=usuarios&msg=atualizado');
        } catch (PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
        }
    }
    else {
        header('Location: sistema.php?page=usuarios');
    }
?>