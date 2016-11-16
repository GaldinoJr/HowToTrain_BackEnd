<?php

  $app->group('/plano-treino',function(){

    $this->post('/cadastrar','HowToTrain\Rotas\PlanoTreino:cadastrarPlanoTreino');

  });

 ?>
