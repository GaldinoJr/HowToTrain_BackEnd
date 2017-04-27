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
    
    class clsPlanoTreino {
        public $codigoExterno = -1;
    }
    
    class clsInformacaoRetorno {
       public $status  = "";
       public function __construct(clsPlanoTreino $objPlanoTreino)
        {
          $this->objPlanoTreino = $objPlanoTreino;
        }
   }
    // PARA EXIBIR O XML NO NAVEGADOR, DESCOMENTAR
    //   header('Content-Type: application/xml');
    //   
    //
    if(strcmp('SEND-json', $_POST['method']) == 0)
    {
        $objPlanoTreino = new clsInformacaoRetorno(new clsPlanoTreino());
       
        $jsPlanoTreino = utf8_encode($_POST['json']);
        $jsPlanoTreino = json_decode($jsPlanoTreino);
        // Pega os parametros 
        $operacao = $jsPlanoTreino->operacao;
        $objJsPlanoTreino = json_decode($jsPlanoTreino->jsonPlanoTreino);
        if(strcmp($operacao,"salvar") == 0)
        {
            // Parametros esperados pela proc:
            // Codido do professor: INT - Referente ao professor que está incluindo o treino
            // Nome do plano de treino: STRING - nome do plano de treino
            
            $proc = 'pr_plano_treino_inclui';
            // TESTE
            //$chamada_proc = "CALL pr_plano_treino_inclui(2,'TESTE 123')";
            $chamada_proc = "CALL " . $proc . " (" . $objJsPlanoTreino->codigoProfessor . ", '" . $objJsPlanoTreino->{'nomePlanoTreino'} . "')";
            // Execulta
            $result = mysqli_query($connect,$chamada_proc);
            
            if (!$result) 
            {
                $objPlanoTreino->status = -1; // Não conseguiu registrar o plano de treino
                mysqli_close($connect);
            }
            else
            {
                if (mysqli_num_rows($result) > 0) 
                {
                    //while ($row = $result->fetch_assoc())
                    //{
                        $row = $result->fetch_assoc();
                        $objPlanoTreino->objPlanoTreino->codigoExterno = $row["cd_plano_treino"];
                    //}
                        $objPlanoTreino->status = 0;
                }
                else 
                {
                    $objPlanoTreino->status = -2;// ***DOCUMENTAR
                }
                mysqli_close($connect);
            }
            echo json_encode($objPlanoTreino);
        }
        else if(strcmp($operacao,"alterar") == 0)
        {
            $objPlanoTreino->status = -2;
            // Parametros esperados pela proc:
            // XML: TEXT - XML contendo as informaões do plano de treino, treinos e exes.
            // Código: INT do plano de treino que será alterado.
            $proc = 'pr_plano_treino_altera';
            
            // RECEBE O JSON DO ANDROID
            // STRING PARA TESTE
            //$objJsPlanoTreino = utf8_encode('{"codigoExterno":-1,"codigoProfessor":-1,"dsPlanoTreino":"decreto teste","indNivelTreino":1,"indSincronizou":-1,"indTipoTreino":0,"nomePlanoTreino":"teste","nrValidadeDias":-1,"objListTreino":[{"fgCarga":0,"idGrupo":1,"nome":"teste","objListExercicio":[{"idGrupo":1,"idImage":0,"listRepeticao":[10,8,6],"nome":"Com giro","nomeLogico":"gifComGiro","nomeLogicoFoto":"abdomen_com_giro","DF_ID":"ID","ID":"1"},{"idGrupo":1,"idImage":0,"listRepeticao":[6,5,4],"nome":"Elevacao de pernas","nomeLogico":"gifElevacaoDePernas","nomeLogicoFoto":"abdomen_elevacao_de_pernas","DF_ID":"ID","ID":"4"},{"idGrupo":1,"idImage":0,"listRepeticao":[12,10],"nome":"Elevacao de joelhos","nomeLogico":"gifElevacaoDeJoelhos","nomeLogicoFoto":"abdomen_elevacao_de_joelhos","DF_ID":"ID","ID":"2"},{"idGrupo":1,"idImage":0,"listRepeticao":[5],"nome":"Elevacao de pernas com peso","nomeLogico":"gifElevacaoDePernasComPeso","nomeLogicoFoto":"abdomen_elevacao_de_pernas_com_peso","DF_ID":"ID","ID":"5"}],"DF_ID":"ID","ID":"9"},{"Descricao":"Atleta ifbb,patrocinada BlackSkull,campeã catarinense,top 6 arnold,musa fitness","dsNomeFoto":"foto_peito_jessica_brum","fgCarga":1,"idGrupo":4,"indNivel":1,"indSexo":0,"indTipoTreino":0,"nome":"Jessica Brum","objListExercicio":[{"descricao":"Em pé com a barra apoiada nos ombros e os pés afastados em distância igual à largura dos ombros. Mantenha as costas retas e dobre ligeiramente os joelhos ao mesmo tempo que direciona o glúteo para trás até que as coxas fiquem paralelas ao chão. Estenda as pernas para retornar à posição inicial.\r\n","idGrupo":4,"idImage":0,"listRepeticao":[12,12,12,12],"nome":"Agachamento livre","nomeLogico":"gifAgachamentoLivre","nomeLogicoFoto":"coxa_agachamento_livre","DF_ID":"ID","ID":"60"},{"descricao":"Sente-se no aparelho de leg press e coloque os pés com afastamento na plataforma igual à largura dos ombros. Lentamente, abaixe o peso até que os joelhos estejam com 90 graus de flexão. Empurre o peso de volta à posição inicial(com as pernas estendidas). \r\n","idGrupo":4,"idImage":0,"listRepeticao":[12,12,12,12],"nome":"Leg Press 45","nomeLogico":"gifLegPress45","nomeLogicoFoto":"coxa_leg_press_45","DF_ID":"ID","ID":"72"},{"descricao":"Sente-se no aparelho e coloque os pés por baixo dos rolos. Levante as pernas para cima, até que os joelhos estejam estendidos. Abaixe as pernas de volta à posição inicial, com os joelhos dobrados em 90 graus.\r\n","idGrupo":4,"idImage":0,"listRepeticao":[12,12,12,12],"nome":"Cadeira extensora","nomeLogico":"gifCadeiraExtensora","nomeLogicoFoto":"coxa_cadeira_extensora","DF_ID":"ID","ID":"68"},{"descricao":"Sentado no aparelho, joelhos estendidos, tornozelos posicionados sobre o apoio, coxas apoiadas, mãos segurando os pegadores. Inspirar e realizar uma flexão dos joelhos, expirando no final do movimento.\r\n","idGrupo":4,"idImage":0,"listRepeticao":[12,12,12,12],"nome":"Cadeira flexora","nomeLogico":"gifCadeiraFlexora","nomeLogicoFoto":"coxa_cadeira_flexora","DF_ID":"ID","ID":"70"}],"DF_ID":"ID","ID":"3"}],"tipoPlanoTreino":1,"DF_ID":"ID","ID":"1"}');
            $codigoExterno = 24;
            //$codigoPlanoTreino = $objJsPlanoTreino->codigo;
            // MONTA XML
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';

            $xml .= '<tagPlanoTreino>';
                $xml .= '<codigoProfessor>' . $objJsPlanoTreino->codigoProfessor . '</codigoProfessor>';
                $xml .= '<dsPlanoTreino>' . $objJsPlanoTreino->{'dsPlanoTreino'} . '</dsPlanoTreino>';
                $xml .= '<indNivelTreino>' . $objJsPlanoTreino->indNivelTreino . '</indNivelTreino>';
                //        $xml .= '<indTipoTreino>' . $objJsPlanoTreino->{'indTipoTreino'} . '</indTipoTreino>';
                $xml .= '<nomePlanoTreino>' . $objJsPlanoTreino->{'nomePlanoTreino'} . '</nomePlanoTreino>';
                $xml .= '<nrValidadeDias>' . $objJsPlanoTreino->nrValidadeDias . '</nrValidadeDias>';
                $xml .= '<tipoPlanoTreino>' . $objJsPlanoTreino->tipoPlanoTreino . '</tipoPlanoTreino>';
                // TREINOS
                $treinos = $objJsPlanoTreino->objListTreino;
                if(!is_null($treinos))
                {
                    foreach ( $treinos as $t )
                    {
                        $xml .= '<tagListTreino>';// Abre o XML DE TREINOS
                        $xml .= '<idGrupo>' . $t->idGrupo . '</idGrupo>';
                        $xml .= '<nome>' . $t->{'nome'} . '</nome>';
                        // EXERC�CIOS
                        $exercicios = $t-> objListExercicio;
                        if(!is_null($exercicios))
                        {
                            foreach ( $exercicios as $e )
                            {
                                $xml .= '<tagListExercicio>'; // Abre o XML DE EXERC�CIOS
                                $xml .= '<nome>' . $e->{'nome'} . '</nome>';
                                $xml .= '<nomeLogicoFoto>' . $e->{'nomeLogicoFoto'} . '</nomeLogicoFoto>';
                                $xml .= '<ID>' . $e->ID . '</ID>';
                                // REPETI��ES
                                $repeticoes = $e->listRepeticao;
                                if(!is_null($repeticoes))
                                {
                                    for ( $i = 0; $i < count( $repeticoes ); $i++ ) 
                                    {
                                        $xml .= '<tagListRepeticao>'; // Abre o XML DE REPETI��ES
                                        $xml .= '<nrRepeticao>' . $repeticoes[$i] . '</nrRepeticao>';
                                        $xml .= '</tagListRepeticao>'; // Fecha o XML DE REPETI��ES
                                    }
                                }
                                $xml .= '</tagListExercicio>'; // Fecha o XML DE EXERC�CIOS
                            }
                        }
                        $xml .= '</tagListTreino>'; // Fecha o XML de TREINOS
                    }
                }
            $xml .= '</tagPlanoTreino>';
            // Monta a chamada da proc
            $chamada_proc = "CALL " . $proc . " ('" .$xml . "', " . $codigoExterno . ")";
            // Execulta
            //echo $chamada_proc;
            //ECHO json_encode($chamada_proc);
            $result = mysqli_query($connect,$chamada_proc);
            $return = array();

            if (!$result) 
            {
                $objPlanoTreino->status = -1; // Não conseguiu registrar o plano de treino
                mysqli_close($connect);
            }
            else
            {
                $objPlanoTreino->status = 0;
                mysqli_close($connect);
            }
            echo json_encode($objPlanoTreino);
        }// Alteração
    } // Método SEND
?>