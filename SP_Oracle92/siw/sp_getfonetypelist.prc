create or replace procedure SP_GetFoneTypeList
   (p_result    out sys_refcursor) is
begin
   -- Recupera o tipos de telefones existentes
   open p_result for 
      select a.sq_tipo_telefone, a.nome, a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc, b.nome sq_tipo_pessoa
        from co_tipo_telefone a, co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa;  
end SP_GetFoneTypeList;
/

