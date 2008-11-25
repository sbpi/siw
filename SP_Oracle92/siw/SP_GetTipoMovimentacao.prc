create or replace procedure SP_GetTipoMovimentacao
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_nome            in  varchar2 default null,
    p_entrada         in  varchar2 default null,
    p_saida           in  varchar2 default null,
    p_orcamentario    in  varchar2 default null,
    p_consumo         in  varchar2 default null,
    p_permanente      in  varchar2 default null,
    p_inativa_bem     in  varchar2 default null,
    p_ativo           in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera as categorias de diárias
   if p_restricao is null then  
     open p_result for
       select sq_tipo_movimentacao as chave, a.cliente,     a.nome,       a.entrada,       a.saida, 
              a.orcamentario,                a.consumo,     a.permanente, a.inativa_bem,   a.ativo,
              case a.entrada         when 'S' then 'Sim' else 'Não' end nm_entrada,
              case a.saida           when 'S' then 'Sim' else 'Não' end nm_saida,
              case a.orcamentario    when 'S' then 'Sim' else 'Não' end nm_orcamentario,
              case a.consumo         when 'S' then 'Sim' else 'Não' end nm_consumo,
              case a.permanente      when 'S' then 'Sim' else 'Não' end nm_permanente,
              case a.inativa_bem     when 'S' then 'Sim' else 'Não' end nm_inativa_bem,
              case a.ativo           when 'S' then 'Sim' else 'Não' end nm_ativo
       from   mt_tipo_movimentacao a
       where  a.cliente = p_cliente
           and (p_chave        is null or (p_chave        is not null and a.sq_tipo_movimentacao = p_chave))
           and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
           and (p_entrada      is null or (p_entrada      is not null and a.entrada  = p_entrada))
           and (p_saida        is null or (p_saida        is not null and a.saida  = p_saida))
           and (p_orcamentario is null or (p_orcamentario is not null and a.orcamentario  = p_orcamentario))
           and (p_consumo      is null or (p_consumo      is not null and a.consumo  = p_consumo))
           and (p_permanente   is null or (p_permanente   is not null and a.permanente  = p_permanente))
           and (p_inativa_bem  is null or (p_inativa_bem  is not null and a.inativa_bem  = p_inativa_bem))
           and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));
   end if;
end SP_GetTipoMovimentacao;
/
