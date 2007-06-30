create or replace procedure SP_GetLcCriterio
   (p_chave        in  number default null,
    p_cliente      in  number,
    p_nome         in  varchar2 default null,
    p_ativo        in  varchar2 default null,    
    p_padrao       in  varchar2 default null,
    p_item         in  varchar2 default null,
    p_restricao    in  varchar2 default null,
    p_result       out sys_refcursor) is
begin
   If p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
      select a.sq_lcjulgamento chave
        from lc_julgamento a
       where a.cliente = p_cliente 
         and a.sq_lcjulgamento    <> coalesce(p_chave,0)
         and ((p_nome   is null) or (p_nome   is not null and a.nome            = p_nome))
         and ((p_padrao is null) or (p_padrao is not null and a.padrao          = p_padrao));
   Else
     open p_result for 
      select a.sq_lcjulgamento chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao,
             a.item, 
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao,
             case a.item when 'S' then 'Sim' else 'Não' end nm_item
        from lc_julgamento a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcjulgamento = p_chave))
         and ((p_nome  is null) or (p_nome  is not null and a.nome  = p_nome))     
         and ((p_ativo is null) or (p_ativo is not null and a.ativo = p_ativo))
         and ((p_padrao is null) or (p_padrao is not null and a.padrao = p_padrao));         
   End If;
end SP_GetLcCriterio;
/
