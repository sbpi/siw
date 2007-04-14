create or replace procedure SP_GetAcordoAditivo
   (p_cliente     in number   default null,
    p_chave       in number   default null,
    p_chave_aux   in number   default null,
    p_protocolo   in number   default null,
    p_codigo      in varchar2 default null,
    p_inicio      in date     default null,
    p_fim         in date     default null,
    p_prorrogacao in varchar2 default null,
    p_restricao   in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os aditivos do contrato
   If p_restricao is null Then
      open p_result for
         select a.sq_acordo_aditivo, a.sq_siw_solicitacao, a.protocolo, a.codigo, a.objeto, 
                a.inicio, a.fim, a.duracao, a.documento_origem, a.documento_data, a.variacao_valor,
                a.prorrogacao, a.revisao, a.acrescimo, a.supressao, a.observacao, a.valor_reajuste, 
                a.parcela_reajustada, a.sq_cc,
                case a.prorrogacao when 'S' then 'Sim' else 'Não' end nm_prorrogacao,
                case a.revisao     when 'S' then 'Sim' else 'Não' end nm_revisao,
                case a.acrescimo
                     when 'S' then 'Acréscimo'
                     else case a.supressao
                               when 'S'
                               then 'Supressão'
                               else '---'
                          end 
                end nm_tipo,
                b.cliente,
                c.prazo_indeterm
           from ac_acordo_aditivo           a
                inner   join ac_acordo      b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner join ac_tipo_acordo c on (b.sq_tipo_acordo     = c.sq_tipo_acordo)
          where b.cliente = p_cliente
            and ((p_chave       is null) or (p_chave        is not null and a.sq_acordo_aditivo  = p_chave))      
            and ((p_chave_aux   is null) or (p_chave_aux    is not null and a.sq_siw_solicitacao = p_chave_aux))
            and ((p_protocolo   is null) or (p_protocolo    is not null and a.protocolo          = p_protocolo))
            and ((p_codigo      is null) or (p_codigo       is not null and a.codigo like '%'||p_codigo||'%'))
            and ((p_inicio      is null) or (p_inicio       is not null and a.inicio between p_inicio and p_fim))
            and ((p_prorrogacao is null) or (p_prorrogacao  is not null and a.prorrogacao        = p_prorrogacao));
   End If;
end SP_GetAcordoAditivo;
/
