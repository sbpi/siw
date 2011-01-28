create or replace FUNCTION SP_GetImpostoIncid
   (p_cliente    numeric,
    p_chave      numeric,
    p_documento  numeric,
    p_lancamento numeric,
    p_restricao  varchar,
    p_result     REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
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
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;