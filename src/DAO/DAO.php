<?php

  namespace HowToTrain\DAO

  class DAO{

    public static $self;

    private function __construct($ci = null)
    {

    }

    public static function getInstance($ci = null){

      if(!isset(self::$self)){
        self::$self = new self($ci);
      }

      return self::$self;
    }



  }

 ?>
