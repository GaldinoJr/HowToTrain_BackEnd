delimiter ;;

drop procedure if exists pr_estado_inclui_altera;;

create procedure pr_estado_inclui_altera(IN p_ds_estado VARCHAR(220), OUT p_ultimo_cd_usado INT)

begin

    SET p_ultimo_cd_usado = (select cd_estado from tb_estado where ds_estado = p_ds_estado);

    IF(ISNULL(p_ultimo_cd_usado)) THEN -- Se não existe, insere
        INSERT INTO tb_estado (ds_estado,cd_pais) VALUES(p_ds_estado,1);
        SET p_ultimo_cd_usado = LAST_INSERT_ID();
    ELSE -- se existe altera
        UPDATE tb_estado
        SET ds_estado = p_ds_estado,
            cd_pais = 1
        WHERE cd_estado = p_ultimo_cd_usado;
    END IF;
    -- Devolve o ID correspondente
end

;;
--INSERTE
--CALL pr_estado_inclui_altera(-1, 'Rio de Janeiro',1)
--ALTERA
--CALL pr_estado_inclui_altera(14, 'São paulo',1)