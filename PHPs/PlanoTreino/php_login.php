<?php
    $host = "mysql.hostinger.com.br";
    $bd = "u713684323_htt";
    $user = "u713684323_htt";
    $senha = "Htt@1234";

    $connect = mysqli_connect($host,$user,$senha,$bd);
    if (mysqli_connect_errno())
    {
        die("Failha ao conectar ao MySQL: " . mysqli_connect_error());
    }
    
    class clsRetorno {
       public $status  = "";
    }
    $DF_SALVAR = "salvar";
    $DF_ALTERAR = "alterar";
    $DF_CONSULTAR = "consultar";
    $DF_EXCLUIR = "excluir";
    
    $objRetorno = new clsRetorno();
        
    $objJson = utf8_encode($_POST['json']);
    $objJson = json_decode($objJson);
    // Pega os parametros 
    $objJsLogin = json_decode($objJson->jsonLogin);
    if(strcmp($_POST['method'],$DF_CONSULTAR) == 0)
    {  
        // Parametros esperados pela proc:
        // - Email do usuário 
        // - Senha criptografada
        $proc = 'pr_login_seleciona';
        // TESTE
        //$chamada_proc = "CALL pr_login_seleciona('emailteste.com','W35KSvro1XlBC/rrVBmSZw==')";
        $chamada_proc = "CALL " . $proc . " ('" . $objJsLogin->dsEmail . "', '" . $objJsLogin->dsSenha . "')";
        // Execulta
        $result = mysqli_query($connect,$chamada_proc);

        if (!$result) 
        {
            $objRetorno->status = -1; // Não conseguiu registrar o plano de treino, vazio
            mysqli_close($connect);
        }
        else
        {
            if (mysqli_num_rows($result) > 0) 
            {
                $row = $result->fetch_assoc();
                $fgPermissao = $row["fg_permissao"];

                if($fgPermissao == 1)
                {
                    $objRetorno->status = 0;
                }
                else
                {
                    $objRetorno->status = -3; // Permissão não consedida
                }
            }
            else 
            {
                $objRetorno->status = -2; // Não conseguiu registrar o plano de treino, não é vazio, mas não tem conteudo
            }
            mysqli_close($connect);
        }
        echo json_encode($objRetorno);
    } // Método SEND
?>