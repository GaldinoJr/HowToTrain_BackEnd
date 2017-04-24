delimiter ;;
DROP PROCEDURE IF EXISTS pr_plano_treino_altera;;
create procedure pr_plano_treino_altera(IN XMLINPUT TEXT, IN V_CD_PLANO_TREINO INT UNSIGNED)
BEGIN
	DECLARE VALUECOUNT_TREINO INT UNSIGNED;
    DECLARE VALUECOUNT_EXERCICIO INT UNSIGNED;
    DECLARE VALUECOUNT_REPETICAO INT UNSIGNED;
	DECLARE VALUE INT UNSIGNED;
	DECLARE I INT UNSIGNED DEFAULT 1;
    DECLARE J INT UNSIGNED DEFAULT 1;
    DECLARE K INT UNSIGNED DEFAULT 1;
    
    DECLARE tagPlanoTreino VARCHAR(200);
    DECLARE tagListTreino VARCHAR(200);
    DECLARE tagListExercicio VARCHAR(200);
    DECLARE tagListRepeticao VARCHAR(200);
    
    DECLARE cdPlanoTreinoInserido INT;
    DECLARE cdTreinoInserido INT;
    DECLARE cdExercicioInserido INT;
    DECLARE cdTreinoExercicioInserido INT;
    
    DECLARE xpathTreino TEXT;
    DECLARE xpathExercicio TEXT;
    DECLARE xpathRepeticao TEXT;
    -- não pode ter dois selets
    -- SELECT VALUECOUNT; -- print 5
    
	-- SELECT EXTRACTVALUE(XMLINPUT, '/tagPlanoTreino/dsPlanoTreino'); -- will print value tag  
    
    -- PASSOS DO SCRIPT (7)
    
    -- 0 - Exclui todas as vinculações de informações do plano de treino
    -- 1 - Altera as informações do plano de treino
    -- 2 - Insere as informações de todos os treinos
    -- 3 - Vincula o treino ao plano de treino
    -- 4 - Insere os exercícios que ainda não estão cadastrados( RETIRAR ESSE PASSO QUANDO TODOS JÁ ESTIVEREM CADASTRADOS)
    -- 5 - Vincula os exerícios aos treinos correspondentes
    -- 6 - Insere as repetições de cada exercício

    
      SET tagPlanoTreino = 'tagPlanoTreino';
      SET tagListTreino = 'tagListTreino';
      SET tagListExercicio = 'tagListExercicio';
      SET tagListRepeticao = 'tagListRepeticao';
-- -----------------------------------------------------------------------------
--   PASSO 0/6 - Exclui todas as vinculações desse plano de treino
-- -----------------------------------------------------------------------------
	DELETE FROM tb_treino_exercicio_repeticao
    WHERE cd_treino_exercicio in 
    (
		SELECT cd_treino_exercicio 
		FROM tb_treino_exercicio 
		WHERE cd_treino in 
		( 
			SELECT cd_treino 
			FROM tb_plano_treino_treino 
            WHERE cd_plano_treino = V_CD_PLANO_TREINO
		)
	);
	DELETE FROM tb_treino_exercicio 
	WHERE cd_treino in 
	( 
		SELECT cd_treino 
		FROM tb_plano_treino_treino 
		WHERE cd_plano_treino = V_CD_PLANO_TREINO
	);
    DELETE FROM tb_plano_treino_treino WHERE cd_plano_treino = V_CD_PLANO_TREINO;
    DELETE FROM tb_treino WHERE cd_plano_treino = V_CD_PLANO_TREINO;
-- -----------------------------------------------------------------------------
--   PASSO 1/6 - Altera as informações do plano de treino
-- -----------------------------------------------------------------------------
     UPDATE tb_plano_treino pt
		SET pt.cd_professor = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/codigoProfessor'))
 		 , pt.ds_plano_treino = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/dsPlanoTreino'))
         , pt.ind_nivel_treino = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/indNivelTreino'))
         , pt.ds_nome_plano_treino = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/nomePlanoTreino'))
         , pt.nr_validade_dias = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/nrValidadeDias'))
         , pt.ind_tipo_treino = EXTRACTVALUE(XMLINPUT, concat('/',tagPlanoTreino,'/tipoPlanoTreino'))
	WHERE pt.cd_plano_treino = V_CD_PLANO_TREINO;
 	 
		 -- EXTRACTVALUE(XMLINPUT, '/tagPlanoTreino/codigoProfessor'),

    
    -- SET cdPlanoTreinoInserido = LAST_INSERT_ID();
-- -----------------------------------------------------------------------------
-- PASSO 2/6 - Insere as informações de todos os treinos
-- -----------------------------------------------------------------------------
    SET VALUECOUNT_TREINO:=EXTRACTVALUE(XMLINPUT, concat('COUNT(/',tagPlanoTreino,'/',tagListTreino,')'));
    -- SELECT VALUECOUNT_TREINO; -- print 5
    WHILE (I <= VALUECOUNT_TREINO) DO 
		SET J := 1;
		SET xpathTreino := concat('/',tagPlanoTreino,'/',tagListTreino,'[', I, ']');

        INSERT INTO tb_treino
		(
			cd_grupo,
			ds_nome_treino
		)
		VALUES
		(
			EXTRACTVALUE(XMLINPUT,concat(xpathTreino,'/idGrupo')),
            EXTRACTVALUE(XMLINPUT,concat(xpathTreino,'/nome'))
		);
        SET cdTreinoInserido = LAST_INSERT_ID();
-- -----------------------------------------------------------------------------
-- PASSE 3/6 - Vincula o treino ao plano de treino
-- -----------------------------------------------------------------------------
		INSERT INTO tb_plano_treino_treino
		(
			cd_plano_treino,
			cd_treino
		)
		VALUES
        (
			V_CD_PLANO_TREINO,
			cdTreinoInserido
        );
        -- -----------------------------------------------------------------------------
-- PASSE 4/6 - Insere os exercícios que ainda não estão cadastrados
-- -----------------------------------------------------------------------------
		
		SET VALUECOUNT_EXERCICIO:=EXTRACTVALUE(XMLINPUT, concat('COUNT(',xpathTreino,'/',tagListExercicio,')'));
        WHILE(J <= VALUECOUNT_EXERCICIO) DO
			SET K := 1;
			SET xpathExercicio := concat(xpathTreino,'/',tagListExercicio,'[', J, ']');

			INSERT INTO tb_exercicio
			(
				cd_exercicio_app,
				ds_nome_exercicio,
                ds_nome_logico_exercicio
                -- ds_exercicio,
--                 cd_grupo
			)
			VALUES
			(
				EXTRACTVALUE(XMLINPUT,concat(xpathExercicio,'/ID')),
				EXTRACTVALUE(XMLINPUT,concat(xpathExercicio,'/nome')),
                EXTRACTVALUE(XMLINPUT,concat(xpathExercicio,'/nomeLogicoFoto'))
                -- EXTRACTVALUE(XMLINPUT,concat('/',tagPlanoTreino,'/',tagListTreino,'/',tagListExercicio,'[',I,']/nome')),
--                 EXTRACTVALUE(XMLINPUT,concat('/',tagPlanoTreino,'/',tagListTreino,'/',tagListExercicio,'[',I,']/nome'))
			);
            SET cdExercicioInserido = LAST_INSERT_ID();
            -- IF(cdExercicioInserido != NULL)
-- -----------------------------------------------------------------------------
-- PASSE 5/6 - Vincula os exerícios aos treinos correspondentes
-- -----------------------------------------------------------------------------
            INSERT INTO tb_treino_exercicio
			(
				cd_treino,
				cd_exercicio
			)
			VALUES
			(
				cdTreinoInserido,
                cdExercicioInserido
			);
            SET cdTreinoExercicioInserido = LAST_INSERT_ID();
-- -----------------------------------------------------------------------------
-- PASSE 6/6 - Insere as repetições de cada exercício
-- -----------------------------------------------------------------------------
			SET VALUECOUNT_REPETICAO:=EXTRACTVALUE(XMLINPUT, concat('COUNT(',xpathExercicio,'/',tagListRepeticao,')'));
            WHILE(K <= VALUECOUNT_REPETICAO) DO
				SET xpathRepeticao := CONCAT(xpathExercicio,'/',tagListRepeticao,'[', K ,']');
				INSERT INTO tb_treino_exercicio_repeticao
				(
					cd_treino_exercicio,
					nr_repeticoes
				)
				VALUES
				(
					cdTreinoExercicioInserido,
					EXTRACTVALUE(XMLINPUT,concat(xpathRepeticao,'/nrRepeticao'))
				);
            SET K:= K+1;
            END WHILE;
-- --------
        SET J:= J+1;
        END WHILE;
-- --------
    SET I:= I+1;
    END WHILE;
	
end
;;

-- XML DE TESTE
-- CALL pr_plano_treino_altera ('<?xml version="1.0" encoding="UTF-8"?><tagPlanoTreino><codigoProfessor>-1</codigoProfessor><dsPlanoTreino>decreto teste meu</dsPlanoTreino><indNivelTreino>1</indNivelTreino><nomePlanoTreino>teste</nomePlanoTreino><nrValidadeDias>-1</nrValidadeDias><tipoPlanoTreino>1</tipoPlanoTreino><tagListTreino><idGrupo>1</idGrupo><nome>teste</nome><tagListExercicio><nome>Com giro</nome><nomeLogicoFoto>abdomen_com_giro</nomeLogicoFoto><ID>1</ID><tagListRepeticao><nrRepeticao>10</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>8</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>6</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Elevacao de pernas</nome><nomeLogicoFoto>abdomen_elevacao_de_pernas</nomeLogicoFoto><ID>4</ID><tagListRepeticao><nrRepeticao>6</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>5</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>4</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Elevacao de joelhos</nome><nomeLogicoFoto>abdomen_elevacao_de_joelhos</nomeLogicoFoto><ID>2</ID><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>10</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Elevacao de pernas com peso</nome><nomeLogicoFoto>abdomen_elevacao_de_pernas_com_peso</nomeLogicoFoto><ID>5</ID><tagListRepeticao><nrRepeticao>5</nrRepeticao></tagListRepeticao></tagListExercicio></tagListTreino><tagListTreino><idGrupo>4</idGrupo><nome>Jessica Brum</nome><tagListExercicio><nome>Agachamento livre</nome><nomeLogicoFoto>coxa_agachamento_livre</nomeLogicoFoto><ID>60</ID><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Leg Press 45</nome><nomeLogicoFoto>coxa_leg_press_45</nomeLogicoFoto><ID>72</ID><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Cadeira extensora</nome><nomeLogicoFoto>coxa_cadeira_extensora</nomeLogicoFoto><ID>68</ID><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao></tagListExercicio><tagListExercicio><nome>Cadeira flexora</nome><nomeLogicoFoto>coxa_cadeira_flexora</nomeLogicoFoto><ID>70</ID><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao><tagListRepeticao><nrRepeticao>12</nrRepeticao></tagListRepeticao></tagListExercicio></tagListTreino></tagPlanoTreino>', 24)
-- RETORNO: APENAS ALTERA AS INFORMAÇÕES NOVAS, DO PLANO DE TREINO, EXCLUINDO TUDO QUE É VINCULADO A ELE, INCLUINDO E VINCULANDO NOVAMENTE.