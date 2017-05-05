delimiter ;;
DROP PROCEDURE IF EXISTS pr_login_seleciona;;
CREATE PROCEDURE pr_login_seleciona(IN V_DS_EMAIL VARCHAR(255), IN V_DS_SENHA VARCHAR(256))
BEGIN
	IF EXISTS (SELECT 1 FROM tb_login WHERE ds_email = V_DS_EMAIL AND ds_senha = V_DS_SENHA) THEN
		SELECT 1 AS fg_permissao;
	ELSE
		SELECT 0 AS fg_permissao;
	END IF;
    
END
;;

-- CHAMADA DE TESTE
-- CALL pr_login_seleciona('emailteste.com','W35KSvro1XlBC/rrVBmSZw==');