create or replace procedure SP_GetCategoriaDiaria
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_nome            in  varchar2 default null,
    p_ativo           in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera as categorias de diárias
   open p_result for
      select a.sq_categoria_diaria as chave, a.cliente, a.nome, a.ativo, a.tramite_especial, a.dias_prestacao_contas,
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
             case a.tramite_especial when 'S' then 'Sim' else 'Não' end as nm_tramite_especial
        from pd_categoria_diaria a
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_categoria_diaria = p_chave))
         and (p_nome       is null or (p_nome       is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo  = p_ativo));
end SP_GetCategoriaDiaria;
/
