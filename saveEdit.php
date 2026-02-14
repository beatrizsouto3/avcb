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
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $celular = $_POST['celular'];
        $permissao_id = $_POST['permissao_id'];

        $data_referencia = !empty($_POST['data_referencia']) ? $_POST['data_referencia'] : null;

        if($tipo_cliente == 'PF'){
            $cpf_cnpj = $_POST['cpf'];
            if(empty($nome) || empty($cpf_cnpj) || empty($email) || empty($celular)){
                header("Location: edit.php?id=$id&erro=obrigatorios");
                exit;
            }
        } else {
            $cpf_cnpj = $_POST['cnpj'];
            if(empty($nome) || empty($cpf_cnpj) || empty($_POST['representante_nome']) || empty($_POST['representante_cpf']) || empty($email) || empty($celular)){
                header("Location: edit.php?id=$id&erro=obrigatorios");
                exit;
            }
        }

        try {
            if(!empty($_POST['senha'])) {
                $senha = md5($_POST['senha']);
                $sql_senha = ", senha = :senha";
            } else {
                $sql_senha = "";
            }

            $sqlInsert = "UPDATE usuarios SET 
                nome = :nome, 
                nome_fantasia = :nome_fantasia, 
                data_nascimento_fundacao = :data_ref, 
                tipo_cliente = :tipo, 
                cpf_cnpj = :id_fiscal, 
                rg = :rg, 
                inscricao_estadual = :ie,
                representante_nome = :rep_n, 
                representante_cpf = :rep_c, 
                representante_cargo = :rep_cargo, 
                representante_email = :rep_email,
                cep = :cep, 
                logradouro = :log, 
                numero = :num, 
                complemento = :comp, 
                bairro = :bairro, 
                cidade = :cid, 
                estado = :est, 
                telefone = :tel, 
                celular = :cel, 
                email = :email, 
                permissao_id = :perm
                $sql_senha
                WHERE id = :id";

            $stmt = $pdo->prepare($sqlInsert);
            
            $params = [
                ':nome' => $nome,
                ':nome_fantasia' => $_POST['nome_fantasia'],
                ':data_ref' => $data_referencia,
                ':tipo' => $tipo_cliente,
                ':id_fiscal' => $cpf_cnpj,
                ':rg' => $_POST['rg'],
                ':ie' => $_POST['inscricao_estadual'],
                ':rep_n' => $_POST['representante_nome'],
                ':rep_c' => $_POST['representante_cpf'],
                ':rep_cargo' => $_POST['representante_cargo'],
                ':rep_email' => $_POST['representante_email'],
                ':cep' => $_POST['cep'],
                ':log' => $_POST['logradouro'],
                ':num' => $_POST['numero'],
                ':comp' => $_POST['complemento'],
                ':bairro' => $_POST['bairro'],
                ':cid' => $_POST['cidade'],
                ':est' => $_POST['estado'],
                ':tel' => $_POST['telefone'],
                ':cel' => $celular,
                ':email' => $email,
                ':perm' => $permissao_id,
                ':id' => $id
            ];

            if(!empty($_POST['senha'])) {
                $params[':senha'] = $senha;
            }

            $stmt->execute($params);

            header('Location: sistema.php?page=usuarios&msg=editado');
            exit;

        } catch (Exception $e) {
            header("Location: edit.php?id=$id&msg=erro");
            exit;
        }
    } else {
        header('Location: sistema.php?page=usuarios');
        exit;
    }
?>