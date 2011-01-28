create or replace FUNCTION SP_GetGpAlteracaoSalario
   (p_chave      numeric,
    p_chave_aux  numeric,
    p_ini        date,
    p_fim        date,    
    p_restricao  varchar,
    p_result     REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
     If p_restricao is null then
      -- Recupera os dados do desempenho do colaborador
       open p_result for 
         select a.sq_alteracao_salario, a.sq_contrato_colaborador as chave,
                a.data_alteracao,       a.novo_valor, a.funcao,
                a.motivo, (select case count(*) when 0 then 'S' else 'N' end
                   from gp_alteracao_salario x
                  where x.data_alteracao        > a.data_alteracao
                    and x.sq_alteracao_salario <> a.sq_alteracao_salario) as ultimo
           from gp_alteracao_salario a
        where a.sq_contrato_colaborador = p_chave
          and (p_chave_aux               is null or (p_chave_aux               is not null and a.sq_alteracao_salario = p_chave_aux))
        order by a.data_alteracao desc;
     End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;