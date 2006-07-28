create or replace procedure SP_PutPD_Atividade
   (p_operacao            in  varchar2,
    p_chave               in  number,
    p_atividade           in  number
   ) is
   w_existe number(18);
begin
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em PD_MISSAO_SOLIC
      select count(*) into w_existe from pd_missao_solic where sq_solic_missao = p_chave and sq_siw_solicitacao = p_atividade;
      
      -- Se ainda n�o existir, insere
      If w_existe = 0 Then
         insert into pd_missao_solic (sq_solic_missao, sq_siw_solicitacao) values (p_chave, p_atividade);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de PD_MISSAO_SOLIC
      delete pd_missao_solic
       where sq_solic_missao    = p_chave
         and sq_siw_solicitacao = p_atividade;
   End If;
end SP_PutPD_Atividade;
/
