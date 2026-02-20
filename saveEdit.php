<?php
    session_start();
    include_once('config.php');

    if((!isset($_SESSION['email']) == true) or (!isset($_SESSION['senha']) == true) or ($_SESSION['permissao'] != 1 && $_SESSION['permissao'] != 3)){
        header('Location: sistema.php');
        exit;
    }

    if(isset($_POST['update'])) {
        $id = $_POST['id'];
        $tipo_cliente = $_POST['tipo_cliente'];
        $cpf_cnpj = ($tipo_cliente == 'PF') ? $_POST['cpf'] : $_POST['cnpj'];

        try {
            $sql_senha = "";
            $params = [
                ':nome' => $_POST['nome'],
                ':nome_fantasia' => $_POST['nome_fantasia'],
                ':data_ref' => !empty($_POST['data_referencia']) ? $_POST['data_referencia'] : null,
                ':tipo' => $tipo_cliente,
                ':id_fiscal' => $cpf_cnpj,
                ':rg' => $_POST['rg'] ?? '',
                ':ie' => $_POST['inscricao_estadual'] ?? '',
                ':rep_n' => $_POST['representante_nome'] ?? '',
                ':rep_c' => $_POST['representante_cpf'] ?? '',
                ':rep_cargo' => $_POST['representante_cargo'] ?? '',
                ':rep_email' => $_POST['representante_email'] ?? '',
                ':cep' => $_POST['cep'],
                ':log' => $_POST['logradouro'],
                ':num' => $_POST['numero'],
                ':comp' => $_POST['complemento'],
                ':bairro' => $_POST['bairro'],
                ':cid' => $_POST['cidade'],
                ':est' => $_POST['estado'],
                ':tel' => $_POST['telefone'],
                ':cel' => $_POST['celular'],
                ':email' => $_POST['email'],
                ':perm' => $_POST['permissao_id'],
                ':perfil' => $_POST['perfil_cliente'] ?? '',
                ':origem' => $_POST['origem_contato'] ?? '',
                ':ponto_ref' => $_POST['ponto_referencia'] ?? '',
                ':obs' => $_POST['observacoes'] ?? '',
                ':id' => $id
            ];

            if(!empty($_POST['senha'])) {
                $params[':senha'] = md5($_POST['senha']);
                $sql_senha = ", senha = :senha";
            }

            $sqlUpdate = "UPDATE usuarios SET 
                nome = :nome, nome_fantasia = :nome_fantasia, data_nascimento_fundacao = :data_ref, 
                tipo_cliente = :tipo, cpf_cnpj = :id_fiscal, rg = :rg, inscricao_estadual = :ie,
                representante_nome = :rep_n, representante_cpf = :rep_c, representante_cargo = :rep_cargo, representante_email = :rep_email,
                cep = :cep, logradouro = :log, numero = :num, complemento = :comp, bairro = :bairro, cidade = :cid, estado = :est, 
                telefone = :tel, celular = :cel, email = :email, permissao_id = :perm,
                perfil_cliente = :perfil, origem_contato = :origem, observacoes = :obs, ponto_referencia = :ponto_ref
                $sql_senha
                WHERE id = :id";

            $stmt = $pdo->prepare($sqlUpdate);
            $stmt->execute($params);

            header('Location: sistema.php?page=usuarios&msg=editado');
            exit;

        } catch (PDOException $e) {
            die("Erro crítico no banco de dados: " . $e->getMessage());
        }
    }
?>