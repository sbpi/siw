create or replace procedure sp_getUnidade_PA
   (p_cliente        in  number,
    p_chave          in  number default null,
    p_ativo          in varchar2 default null,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as unidades do módulo de protocolo
      open p_result for 
         select a.sq_unidade chave, a.registra_documento, a.autua_processo, 
                a.arquivo_setorial, a.ativo, a.sq_unidade_pai,
                case a.ativo              when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.registra_documento when 'S' then 'Sim' else 'Não' end as nm_registra_documento,
                case a.autua_processo     when 'S' then 'Sim' else 'Não' end as nm_autua_processo,
                case a.arquivo_setorial   when 'S' then 'Sim' else 'Não' end as nm_arquivo_setorial,
                b.nome, b.sigla,
                e.nome nm_unidade_pai, e.sigla sg_unidade_pai,
                case when a.sq_unidade_pai is null then a.prefixo              else d.prefixo              end as prefixo, 
                case when a.sq_unidade_pai is null then a.numero_documento     else d.numero_documento     end as numero_documento, 
                case when a.sq_unidade_pai is null then a.numero_tramite       else d.numero_tramite       end as numero_tramite, 
                case when a.sq_unidade_pai is null then a.numero_transferencia else d.numero_transferencia end as numero_transferencia, 
                case when a.sq_unidade_pai is null then a.numero_eliminacao    else d.numero_eliminacao    end as numero_eliminacao,
                a.prefixo||e.nome||coalesce(b.nome,'0') as ordena
           from pa_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                  inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                left    join pa_unidade         d on (a.sq_unidade_pai     = d.sq_unidade)
                  left  join eo_unidade         e on (d.sq_unidade         = e.sq_unidade)
          where a.cliente = p_cliente 
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo   = p_ativo));         
   Elsif p_restricao = 'NUMERADORA' Then
      -- Recupera as unidades numeradoras de protocolo (campo sq_unidade_pai nulo)
      open p_result for 
         select a.sq_unidade chave, a.registra_documento, a.autua_processo, a.prefixo,
                a.numero_documento, a.numero_tramite, a.numero_transferencia, a.numero_eliminacao, 
                a.arquivo_setorial, a.ativo,
                case a.ativo              when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.registra_documento when 'S' then 'Sim' else 'Não' end as nm_registra_documento,
                case a.autua_processo     when 'S' then 'Sim' else 'Não' end as nm_autua_processo,
                case a.arquivo_setorial   when 'S' then 'Sim' else 'Não' end as nm_arquivo_setorial,
                b.nome, b.sigla
           from pa_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                  inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
          where a.cliente        = p_cliente 
            and a.sq_unidade_pai is null
            and a.ativo          = 'S'
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
   Else -- p_restricao recebendo o prefixo da unidade
      -- Recupera as unidades numeradoras de protocolo (campo sq_unidade_pai nulo)
      open p_result for 
         select a.sq_unidade chave, a.registra_documento, a.autua_processo, a.prefixo,
                a.numero_documento, a.numero_tramite, a.numero_transferencia, a.numero_eliminacao, 
                a.arquivo_setorial, a.ativo,
                case a.ativo              when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.registra_documento when 'S' then 'Sim' else 'Não' end as nm_registra_documento,
                case a.autua_processo     when 'S' then 'Sim' else 'Não' end as nm_autua_processo,
                case a.arquivo_setorial   when 'S' then 'Sim' else 'Não' end as nm_arquivo_setorial,
                b.nome, b.sigla
           from pa_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                  inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
          where a.cliente        = p_cliente 
            and a.sq_unidade_pai is null
            and a.sq_unidade     <> coalesce(p_chave,0)
            and a.ativo          = 'S'
            and a.prefixo        = p_restricao;
   End If;
end sp_getUnidade_PA;
/
