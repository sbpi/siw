create or replace procedure SP_GetBankHousList
   (p_sq_banco   in  number,
    p_nome       in  varchar2 default null,
    p_codigo     in  varchar2 default null,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados da agência bancária
   open p_result for 
      select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'Não' end padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco
         and (p_nome   is null or (p_nome   is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and (length(p_codigo)=6 and a.codigo = p_codigo or length(p_codigo)=4 and p_codigo = substr(a.codigo,1,4))));
end SP_GetBankHousList;
/
