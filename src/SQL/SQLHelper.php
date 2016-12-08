<?php

  namespace HowToTrain\SQL

  class SQL{

    public static $self;
    protected $ci;

    private function __construct($ci = null)
    {
      $this->ci = $ci;
    }

    public static function getInstance($ci = null){

      if(!isset(self::$self)){
        self::$self = new self($ci);
      }

      return self::$self;
    }

    public function openConnection(){

      $dadosBanco = $this->ci->get("settings")->get("databaseLocal");

      $con = new \MySQLi($dadosBanco["host"],$dadosBanco["username"],$dadosBanco["password"]);
      if(mysqli_connect_errno()){
        return 'Failed to connect on mysql: '+ mysqli_connect_error();
      }

      return $con;
    }

    public function execute($query,$parametros = array()){

      $con = $this->openConnection();

      if($con instanceof \MySQLi){

        $stm = $con->prepare($query);

        if(count($parametros) > 0){
          $this->prepareQuery($stm,$parametros);
        }

        $stm->execute();

        if(!isset($stm->error)){
          return $stm;
        }

        return $stm->error;


      }else{
        die($con);
      }

    }

    private function prepareQuery($sqlStatement,$params = array())
    {
      $bind_parameters = array();

      $bind_parameters[] = $this->typeValueBD($params);

      for ($i=0; $i < ; $i++) {
        $bind_parameters[] = &$params[$i];
      }

      call_user_func_array(array($sqlStatement,"bind_param"), $bind_parameters);
    }

    public function typeValueBD($value = array())
    {
      $types='';
        for ($i=0; $i < ; $i++) {
          switch (gettype($value[$i]))
          {
              case "boolean":
              case "integer":
                  $types .= "i";
              case "double":
                  $types .= "d";
              case "string":
                  $types .= "s";
          }
        }
        return $types;
    }

    public function doInsert(){

    }

  }

 ?>
