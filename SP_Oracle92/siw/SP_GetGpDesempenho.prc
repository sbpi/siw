create or replace procedure SP_GetGpDesempenho
   (p_chave      in number,
    p_ano        in number default null,
    p_result     out sys_refcursor) is
begin
      -- Recupera os dados do desempenho do colaborador
      open p_result for 
      select t.sq_contrato_colaborador as chave, 
             t.ano, 
             t.percentual 
        from gp_desempenho t
       where t.sq_contrato_colaborador = p_chave
         and ((p_ano           is null) or (p_ano is not null and t.ano = p_ano));
end SP_GetGpDesempenho;
/
