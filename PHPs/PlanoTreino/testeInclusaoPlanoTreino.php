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
        public $codigoPlanoTreino = "";
    }
    
    class clsInformacaoRetorno {
       public $status  = "";
       public function __construct(clsPlanoTreino $objPlanoTreino)
        {
          $this->objPlanoTreino = $objPlanoTreino;
        }
   }
  
    $objPlanoTreino = new clsInformacaoRetorno(new clsPlanoTreino());

    // Parametros esperados pela proc:
    // Codido do professor: INT - Referente ao professor que está incluindo o treino
    // Nome do plano de treino: STRING - nome do plano de treino

    // TESTE
    $chamada_proc = "CALL pr_plano_treino_inclui(2,'TESTE 123')";
    //$chamada_proc = "CALL " . $proc . " (" . $objPlanoTreino->codProfessor . ", '" . $objPlanoTreino->nomePlanoTreino . "')";
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
                $objPlanoTreino->objPlanoTreino->codigoPlanoTreino = $row["cd_plano_treino"];
            //}
        }
        else 
        {
            $objPlanoTreino->status = -2;
        } 
        $objPlanoTreino->status = 0;
        mysqli_close($connect);
    }
    echo json_encode($objPlanoTreino);
        
?>