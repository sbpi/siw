create or replace FUNCTION SP_PutConfPJ
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_siw_solicitacao          numeric,
    p_exibe_relatorio          varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Altera registro
   update pj_projeto
      set exibe_relatorio = p_exibe_relatorio
   where sq_siw_solicitacao = p_siw_solicitacao;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;