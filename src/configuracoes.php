<?php
  return [
    "settings" => [
      "displayErrorDetails" =>true,
      "logger"=>[
        "name" =>"HowToTrain_BackEnd",
        "level" => Monolog\Logger::DEBUG,
        "path" => "logs/",

      ],
      "databaseLocal"=>[
        "host" => "localhost",
        "username" => "root",
        "password" => "nkr5vdyi",
        "database" => "HowToTrain"

      ],
      "foundHandler" => new Slim\Handlers\Strategies\RequestResponseArgs

    ]
  ];
 ?>
