create or replace procedure SP_GetImpostoIncid
   (p_cliente    in number,
    p_chave      in number   default null,
    p_documento  in number   default null,
    p_lancamento in number   default null,
    p_restricao  in varchar2,
    p_result     out sys_refcursor) is
begin
   If p_restricao = 'INCIDENCIA' Then
      -- Verifica se deve haver retenção ou tributos sobre o documento
      open p_result for 
         select case when sum(aliquota_normal)   > 0 then 'S' else 'N' end calcula_tributo, 
                case when sum(aliquota_retencao) > 0 then 'S' else 'N' end calcula_retencao
           from fn_imposto_incid                     a
                inner        join fn_imposto         b on (a.sq_imposto         = b.sq_imposto and
                                                           b.calculo            = 0
                                                          )
                inner        join fn_lancamento      c on (a.sq_tipo_lancamento = c.sq_tipo_lancamento and
                                                           c.sq_siw_solicitacao = p_chave
                                                          )
          where a.sq_tipo_documento = p_documento;
   End If;
End SP_GetImpostoIncid;
/

