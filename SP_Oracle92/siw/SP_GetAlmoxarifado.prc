create or replace procedure SP_GetAlmoxarifado
   (p_cliente         in  number,
    p_chave           in  number   default null,
--    p_chave_aux       in  number   default null,
    p_nome            in  varchar2 default null,
    p_localizacao     in  number   default null,
    p_ativo           in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera os itens de Almoxarifado
   if p_restricao is null Then
 --      if p_chave_aux is null Then
           open p_result for
           select a.sq_almoxarifado as chave, a.cliente, a.sq_localizacao, a.nome, a.ativo, 
                  case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                  b.nome as nm_localizacao, 
                  c.sq_unidade, c.nome as nm_unidade, c.sigla as sg_unidade                                
             from mt_almoxarifado             a 
                  inner   join eo_localizacao b on (a.sq_localizacao = b.sq_localizacao)
                    inner join eo_unidade     c on (b.sq_unidade     = c.sq_unidade)
            where a.cliente = p_cliente
              and (p_chave        is null or (p_chave        is not null and a.sq_almoxarifado = p_chave))
              and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
              and (p_localizacao  is null or (p_localizacao  is not null and a.sq_localizacao = p_localizacao))
              and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));
   else
           open p_result for
           select a.sq_almoxarifado_local as chave, a.sq_almoxarifado, a.sq_local_pai, a.nome, a.ativo, 
                 case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                 d.sq_unidade, d.sigla as sg_unidade
           from mt_almoxarifado_local a
                  inner   join mt_almoxarifado b on (a.sq_almoxarifado = b.sq_almoxarifado)
                  inner   join eo_localizacao c on (b.sq_localizacao = c.sq_localizacao)
                  inner   join eo_unidade     d on (c.sq_unidade =  d.sq_unidade)
            where a.sq_almoxarifado = p_restricao
              and (p_chave        is null or (p_chave        is not null and a.sq_almoxarifado = p_chave))
              and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
              and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));   
   end if;
end SP_GetAlmoxarifado;
/
