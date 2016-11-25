<?php

  namespace HowToTrain\DAO

  class DAO{

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



      }else{
        die($con);
      }

    }


  }

 ?>
