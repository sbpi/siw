create or replace procedure SP_GetAcordoNota
   (p_cliente           in number   default null,
    p_chave             in number   default null,
    p_chave_aux         in number   default null,
    p_sq_tipo_documento in number   default null,
    p_sq_acordo_aditivo in varchar2 default null,
    p_numero            in varchar2 default null,
    p_data              in date     default null,
    p_restricao         in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera as notas do contrato
   If p_restricao is null Then
      open p_result for     
         select a.sq_acordo_nota, a.sq_siw_solicitacao, a.sq_tipo_documento, a.sq_acordo_outra_parte, 
                a.sq_acordo_aditivo, a.numero, a.data, a.valor, a.sq_lcfonte_recurso, 
                a.sq_especificacao_despesa, a.observacao,
                c.nome nm_tipo_documento,
                f.nome_resumido nm_outra_parte,
                e.codigo cd_aditivo, e.sq_cc cc_aditivo,
                g.sq_cc cc_acordo
           from ac_acordo_nota                     a  
                inner   join ac_acordo             b on (a.sq_siw_solicitacao    = b.sq_siw_solicitacao)
                  inner join siw_solicitacao       g on (b.sq_siw_solicitacao    = g.sq_siw_solicitacao)
                inner   join fn_tipo_documento     c on (a.sq_tipo_documento     = c.sq_tipo_documento)
                left    join ac_acordo_outra_parte d on (a.sq_acordo_outra_parte = d.sq_acordo_outra_parte)
                  left  join co_pessoa             f on (d.outra_parte           = f.sq_pessoa)
                left    join ac_acordo_aditivo     e on (a.sq_acordo_aditivo     = e.sq_acordo_aditivo)
          where b.cliente = p_cliente
            and ((p_chave             is null) or (p_chave             is not null and a.sq_acordo_nota     = p_chave))      
            and ((p_chave_aux         is null) or (p_chave_aux         is not null and a.sq_siw_solicitacao = p_chave_aux))
            and ((p_sq_tipo_documento is null) or (p_sq_tipo_documento is not null and a.sq_tipo_documento  = p_sq_tipo_documento))
            and ((p_sq_acordo_aditivo is null) or (p_sq_acordo_aditivo is not null and a.sq_acordo_aditivo  = p_sq_acordo_aditivo))
            and ((p_numero            is null) or (p_numero            is not null and a.numero             = p_numero))
            and ((p_data              is null) or (p_data              is not null and a.data = p_data));
      End If;
end SP_GetAcordoNota;
/
