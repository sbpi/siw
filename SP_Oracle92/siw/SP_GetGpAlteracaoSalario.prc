create or replace procedure SP_GetGpAlteracaoSalario
   (p_chave      in number,
    p_chave_aux  in number,
    p_ini        in date,
    p_fim        in date,    
    p_restricao  in varchar2,
    p_result     out sys_refcursor) is
begin
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
end SP_GetGpAlteracaoSalario;
/
