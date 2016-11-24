<?php

  namespace HowToTrain\Rotas;

  class PlanoTreino{

    protected $ci;

    public function __construct($ci)
    {
      $this->ci = $ci;
    }

    public function cadastrarPlanoTreino($request,$response)
    {
      $novoPlanoTreino = json_decode($request->getBody());
      $sql = null;
      $res;
      try
      {
        $dadosDB = $this->ci->get("settings")->get("databaseLocal");

         $sql = new MySQLi($dadosDB["host"],$dadosDB["username"],$dadosDB["password"]);

        if(mysqli_connect_errno()){
          $resData = array();
          $resData["error"] = "Failed to connect to MySQL: " . mysqli_connect_error();

          $res = $response->withJson($resData,200);
        }

        $sql->select_db($dadosDB["database"]);

        $query = "insert into tb_plano_treino(ds_plano_treino,ind_tipo_treino,nr_dias,cd_professor) values(?,?,?,?);";

        $stm = $sql->prepare($query);

        $valoresQuery = array("siii");
        $valoresQuery[] = &$novoPlanoTreino->nomePlanoTreino;
        $valoresQuery[] = &$novoPlanoTreino->tipoTreino;
        $valoresQuery[] = &$novoPlanoTreino->periodoValidade;
        $valoresQuery[] = &$novoPlanoTreino->codigoProfessor;

        call_user_func_array(array($stm,"bind_param"),$valoresQuery);

        $success = $stm->execute();

        if($success){

          $json = array("codigoCriado"=> $sql->insert_id);



          $res = $response->withJson(array_merge($json,$novoPlanoTreino),200);

        }else{
          $resData = array();
          $resData["error"] = "Failed to connect to MySQL: " . mysqli_connect_error();

          $res = $response->withJson($resData,200);
        }

      }catch(Exception $e){

        $resData = array();
        $resData["error"] = $e->getMessage();

        $res = $response->withJson($resData,200);

      }finally{
        $sql->close();
      }

      return $res;
    }
  }


 ?>
