delimiter ;;

drop procedure if exists pr_cidade_inclui_altera;;

create procedure pr_cidade_inclui_altera(IN p_ds_cidade VARCHAR(220), IN p_cd_estado INT, OUT p_ultimo_cd_usado INT)

begin

    SET p_ultimo_cd_usado = (select cd_cidade from tb_cidade where ds_cidade = p_ds_cidade);

    IF(ISNULL(p_ultimo_cd_usado)) THEN -- Se não existe, insere
        INSERT INTO tb_cidade (ds_cidade,cd_estado) VALUES(p_ds_cidade,p_cd_estado);
        SET p_ultimo_cd_usado = LAST_INSERT_ID();
    ELSE -- se existe altera
        UPDATE tb_cidade
        SET ds_cidade = p_ds_cidade,
            cd_estado = p_cd_estado
        WHERE cd_estado = p_ultimo_cd_usado;
    END IF;
    -- Devolve o ID correspondente
end

;;
--INSERTE
--CALL pr_estado_inclui_altera(-1, 'Rio de Janeiro',1)
--ALTERA
--CALL pr_estado_inclui_altera(14, 'São paulo',1)