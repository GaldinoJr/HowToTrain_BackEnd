<?php

  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Message\ResponseInterface as Response;

  require 'vendor/autoload.php';

  // require_once 'src/Rotas/PlanoTreino.php';

  $configuracoes = require_once 'src/configuracoes.php';

  $contanier = new \Slim\Container($configuracoes);

  $app = new \Slim\App($contanier);

  $app->get('/',function(Request $request,Response $response){
    $response->getBody()->write('Hello Slim PHP');

    return $response;
  });
  //
  // $app->get('/hello/{name}',function(Request $request, Response $response,$args){
  //
  //   $nome = $request->getAttribute('name');
  //
  //    $json = array("nome" => $nome);
  //
  //   $response->getBody()->write(json_encode($json));
  //
  //   return $response;
  //
  // });


 require_once 'src/rotas/roteamento.php';

  $app->run();

?>
