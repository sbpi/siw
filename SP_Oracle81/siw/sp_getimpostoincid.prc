create or replace procedure SP_GetImpostoIncid
   (p_cliente    in number,
    p_chave      in number   default null,
    p_documento  in number   default null,
    p_lancamento in number   default null,
    p_restricao  in varchar2,
    p_result     out siw.sys_refcursor) is
begin
   If p_restricao = 'INCIDENCIA' Then
      -- Verifica se deve haver retenção ou tributos sobre o documento
      open p_result for
         select decode(sum(aliquota_normal),0,'N','S') calcula_tributo,
                decode(sum(aliquota_retencao),0,'N','S') calcula_retencao
           from fn_imposto_incid                     a,
                fn_imposto         b,
                fn_lancamento      c
          where (a.sq_imposto         = b.sq_imposto and
                 b.calculo            = 0
                )
            and (a.sq_tipo_lancamento = c.sq_tipo_lancamento and
                 c.sq_siw_solicitacao = p_chave
                )
            and a.sq_tipo_documento = p_documento;
   End If;
End SP_GetImpostoIncid;
/

