create or replace procedure SP_GetCaixa
   (p_chave       in  number   default null,
    p_cliente     in  number,
    p_unidade     in  number   default null,    
    p_numero      in  number   default null,    
    p_assunto     in  varchar2 default null,     
    p_restricao   in  varchar2 default null,             
    p_result      out sys_refcursor
   ) is
begin
   -- Recupera os grupos da caixa
   open p_result for 
     select a.sq_caixa, a.sq_unidade, a.sq_arquivo_local, a.assunto, a.descricao, 
            a.data_limite, a.numero, a.intermediario, a.destinacao_final, a.arquivo_data, a.arquivo_guia_numero, a.arquivo_guia_ano, 
            a.elimin_data, a.elimin_guia_numero, a.elimin_guia_ano,
            b.nome as nm_unidade, b.sigla as sg_unidade
       from pa_caixa              a 
            inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_caixa         = p_chave  ))
        and ((p_unidade   is null) or (p_unidade   is not null and a.sq_unidade       = p_unidade))
        and ((p_numero    is null) or (p_numero    is not null and a.numero           = p_numero ))
        and ((p_assunto   is null) or (p_assunto   is not null and acentos(a.assunto) like '%' + acentos(p_assunto)+ '%' ))
        and (coalesce(p_restricao,'null') not in ('TRAMITE') or
             (p_restricao = 'TRAMITE' and a.arquivo_data is null)
            );
end SP_GetCaixa;
/
