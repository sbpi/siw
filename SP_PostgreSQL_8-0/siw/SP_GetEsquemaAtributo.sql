CREATE OR REPLACE FUNCTION siw.SP_GetEsquemaAtributo
   (p_restricao           varchar,
    p_sq_esquema_tabela   numeric,
    p_sq_esquema_atributo numeric,
    p_sq_coluna           numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as coluna cadastradas em uma tabela para importação
   open p_result for 
      select a.sq_esquema_atributo, a.sq_esquema_tabela, a.sq_coluna, a.ordem, a.campo_externo,
             a.mascara_data, a.valor_default,
             b.nome as nm_coluna, b.tamanho, b.obrigatorio, b.ordem as or_coluna, b.descricao,
             b.precisao, b.escala,
             c.nome as nm_coluna_tipo
        from siw.dc_esquema_atributo     a 
             inner join siw.dc_coluna    b on (a.sq_coluna    = b.sq_coluna)
             inner join siw.dc_dado_tipo c on (b.sq_dado_tipo = c.sq_dado_tipo) 
       where a.sq_esquema_tabela = p_sq_esquema_tabela
         and ((p_sq_esquema_atributo is null) or (p_sq_esquema_atributo is not null and a.sq_esquema_atributo = p_sq_esquema_tabela))
         and ((p_sq_coluna           is null) or (p_sq_coluna           is not null and a.sq_coluna           = p_sq_coluna));
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric) OWNER TO siw;
