delimiter ;;
DROP PROCEDURE IF EXISTS pr_plano_treino_inclui;;
CREATE PROCEDURE pr_plano_treino_inclui(IN V_CD_PROFESSOR INT, IN V_NOME_PLANO_TREINO VARCHAR(45), OUT V_CD_PLANO_TREINO INT)
BEGIN
	INSERT INTO tb_plano_treino
    (
		ds_nome_plano_treino,
        cd_professor
    )
    VALUES
    (
		V_NOME_PLANO_TREINO,
		V_CD_PROFESSOR
    );
    
    SET V_CD_PLANO_TREINO = LAST_INSERT_ID();
END
;;

-- CHAMADA DE TESTE
-- CALL pr_plano_treino_inclui(2,'TESTE 123',@ultimo_id_inserido);