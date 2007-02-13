create or replace procedure sp_getUnidade_PA
   (p_cliente        in  number,
    p_chave          in  number default null,
    p_ativo          in varchar2 default null,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as unidades de planejamento
      open p_result for 
         select a.sq_unidade chave, a.registra_documento, a.autua_processo, a.prefixo,
                a.numero_documento, a.numero_tramite, a.numero_transferencia, a.numero_eliminacao, 
                a.arquivo_setorial, a.ativo,
                case a.ativo              when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.registra_documento when 'S' then 'Sim' else 'Não' end as nm_registra_documento,
                case a.autua_processo     when 'S' then 'Sim' else 'Não' end as nm_autua_processo,
                case a.arquivo_setorial   when 'S' then 'Sim' else 'Não' end as nm_arquivo_setorial,
                b.nome, b.sigla
           from pa_unidade                          a
                inner   join siw.eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                  inner join siw.co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
          where a.cliente = p_cliente 
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo   = p_ativo));         
   End If;
end sp_getUnidade_PA;
/
