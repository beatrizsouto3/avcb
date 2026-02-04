<?php
    session_start();
    include_once('config.php');

    if(isset($_POST['submit'])){
        
        $codigo = $_POST['codigo'];
        $tipo = $_POST['tipo_documento'];
        $usuario_id = $_SESSION['id_usuario'];

        if(isset($_FILES['arquivo'])){
            $arquivo = $_FILES['arquivo'];
            
            if($arquivo['error'] === UPLOAD_ERR_INI_SIZE || $arquivo['size'] > 5000000){
                header('Location: sistema.php?page=documentos&msg=erro_tamanho');
                exit;
            }

            if($arquivo['error'] == 0){
                
                $pasta = "uploads/";
                
                if(!is_dir($pasta)){
                    mkdir($pasta, 0777, true);
                }

                $nomeOriginal = $arquivo['name'];
                $novoNome = $codigo . "_" . $nomeOriginal;
                
                if(move_uploaded_file($arquivo['tmp_name'], $pasta . $novoNome)){
                    
                    $sql = "INSERT INTO documentos (codigo_identificador, tipo_documento, caminho_arquivo, usuario_id) 
                            VALUES (:codigo, :tipo, :caminho, :usuario_id)";
                    
                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':codigo' => $codigo,
                            ':tipo' => $tipo,
                            ':caminho' => $novoNome,
                            ':usuario_id' => $usuario_id
                        ]);
                        
                        header('Location: sistema.php?page=documentos&msg=doc_sucesso');
                        exit;
                    } catch(PDOException $e) {
                        header('Location: sistema.php?page=documentos&msg=erro_banco');
                        exit;
                    }
                } else {
                    header('Location: sistema.php?page=documentos&msg=erro_pasta');
                    exit;
                }
            }
        }
    }
    header('Location: sistema.php?page=documentos&msg=doc_erro');
?>