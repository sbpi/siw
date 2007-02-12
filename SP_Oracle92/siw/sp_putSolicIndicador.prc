create or replace procedure sp_putSolicIndicador
   (p_operacao            in  varchar2,
    p_chave               in  number default null,
    p_solicitacao         in  number default null,
    p_indicador           in  number default null
   ) is
   w_existe number(18);
begin
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em siw_solic_indicador
      select count(*) into w_existe from siw_solic_indicador where sq_siw_solicitacao = p_solicitacao and sq_eoindicador = p_indicador;
      
      -- Se ainda não existir, insere
      If w_existe = 0 Then
         insert into siw_solic_indicador 
            ( sq_solic_indicador, sq_siw_solicitacao, sq_eoindicador) 
         values (sq_solic_indicador.nextval, p_solicitacao, p_indicador);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de siw_solic_indicador
      delete siw_solic_indicador where sq_solic_indicador = p_chave;
   End If;
end sp_putSolicIndicador;
/
