create or replace FUNCTION sp_putSolicIndicador
   (p_operacao             varchar,
    p_chave                numeric,
    p_solicitacao          numeric,
    p_plano                numeric,
    p_indicador            numeric 
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
BEGIN
   If p_plano is not null Then
      -- Trata indicadores ligados a planos estratégicos
      If p_operacao = 'I' Then
         -- Verifica se o registro existe em pe_plano_indicador
         select count(*) into w_existe from pe_plano_indicador where sq_plano = p_plano and sq_eoindicador = p_indicador;
         
         -- Se ainda não existir, insere
         If w_existe = 0 Then
            insert into pe_plano_indicador 
               ( sq_plano_indicador, sq_plano, sq_eoindicador) 
            values (nextVal('sq_plano_indicador'), p_plano, p_indicador);
         End If;
      Elsif p_operacao = 'E' Then
         -- Remove registro de pe_plano_indicador
         DELETE FROM pe_plano_indicador where sq_plano_indicador = p_chave;
      End If;
   Else
      -- Trata indicadores ligados a solicitações
      If p_operacao = 'I' Then
         -- Verifica se o registro existe em siw_solic_indicador
         select count(*) into w_existe from siw_solic_indicador where sq_siw_solicitacao = p_solicitacao and sq_eoindicador = p_indicador;
         
         -- Se ainda não existir, insere
         If w_existe = 0 Then
            insert into siw_solic_indicador 
               ( sq_solic_indicador, sq_siw_solicitacao, sq_eoindicador) 
            values (nextVal('sq_solic_indicador'), p_solicitacao, p_indicador);
         End If;
      Elsif p_operacao = 'E' Then
         -- Remove registro de siw_solic_indicador
         DELETE FROM siw_solic_indicador where sq_solic_indicador = p_chave;
      End If;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;