create or replace FUNCTION SP_PutPdTarefa
   (p_operacao             varchar,
    p_chave                numeric,
    p_tarefa               numeric
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em PD_MISSAO_SOLIC
      select count(*) into w_existe from pd_missao_solic where sq_solic_missao = p_chave and sq_siw_solicitacao = p_tarefa;
      
      -- Se ainda não existir, insere
      If w_existe = 0 Then
         insert into pd_missao_solic (sq_solic_missao, sq_siw_solicitacao) values (p_chave, p_tarefa);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de PD_MISSAO_SOLIC
      DELETE FROM pd_missao_solic
       where sq_solic_missao    = p_chave
         and sq_siw_solicitacao = p_tarefa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;