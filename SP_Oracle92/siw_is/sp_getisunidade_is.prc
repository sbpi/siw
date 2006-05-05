create or replace procedure SP_GetIsUnidade_IS
   (p_chave          in  number default null,
    p_cliente        in  number,
    p_administrativa in varchar2 default null,
    p_planejamento   in varchar2 default null,
    p_result         out sys_refcursor) is
begin
   -- Recupera as unidades de modulo infra-sig
   open p_result for 
      select a.sq_unidade chave, a.administrativa, a.planejamento , b.nome, b.sigla
        from is_unidade                             a
             left outer join siw.eo_unidade         b on (a.sq_unidade = b.sq_unidade)
             left outer join siw.co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
       where c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
         and ((p_administrativa is null) or (p_administrativa is not null and a.administrativa = p_administrativa))
         and ((p_planejamento   is null) or (p_planejamento   is not null and a.planejamento   = p_planejamento));         
end SP_GetIsUnidade_IS;
/
