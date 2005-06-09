create or replace procedure SP_GetAdressTPList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as defici�ncias existentes
   open p_result for 
      select a.sq_tipo_endereco, a.nome, a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'N�o' end padraodesc,
             case a.email when 'S' then 'Sim' else 'N�o' end email, 
             case a.internet when 'S' then 'Sim' else 'N�o' end internet,
             a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'N�o' end ativodesc, b.nome sq_tipo_pessoa 
        from co_tipo_endereco a, co_tipo_pessoa b  
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa;
end SP_GetAdressTPList;
/

