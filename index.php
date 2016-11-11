<?php

  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Message\ResponseInterface as Response;

  require 'vendor/autoload.php';

  $app = new \Slim\App;

  $app->get('/',function(Request $request,Response $response){
    $response->getBody()->write('Hello Slim PHP');

    return $response;
  });

  $app->get('/hello/{name}',function(Request $request, Response $response,$args){

    $nome = $request->getAttribute('name');

     $json = array("nome" => $nome);

    $response->getBody()->write(json_encode($json));

    return $response;

  });

  $app->run();

?>
