create or replace FUNCTION SP_PutSolicInter
   (p_operacao             varchar,
    p_chave               numeric,
    p_pessoa              numeric,
    p_sq_tipo_interessado numeric,
    p_envia_email         varchar,
    p_tipo_visao          numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_cont numeric(18);
BEGIN
   If p_operacao <> 'I' Then
      -- Se for alteração ou exclusão, faz o tratamento para migração do formato antigo de interessados para o formato novo
      select count(a.sq_solicitacao_interessado) into w_cont from siw_solicitacao_interessado a where a.sq_siw_solicitacao = p_chave and a.sq_pessoa = p_pessoa;
     
      -- Se não existe na nova tabela é porquê precisa migrar
      If w_cont = 0 Then
         If p_operacao = 'A' Then
            -- Insere registro na nova tabela de interessados
            insert into siw_solicitacao_interessado
               (sq_solicitacao_interessado,         sq_siw_solicitacao, sq_pessoa,   sq_tipo_interessado,   envia_email,   tipo_visao)
            values
               (sq_solicitacao_interessado.nextval, p_chave,            p_pessoa,    p_sq_tipo_interessado, p_envia_email, p_tipo_visao);
         End If;
         
         -- Remove das tabelas antigas
         DELETE FROM gd_demanda_interes a where a.sq_siw_solicitacao = p_chave and a.sq_pessoa = p_pessoa;
         DELETE FROM pj_projeto_interes a where a.sq_siw_solicitacao = p_chave and a.sq_pessoa = p_pessoa;
      End If;
   End If;
   
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de interessados
      insert into siw_solicitacao_interessado
         (sq_solicitacao_interessado,         sq_siw_solicitacao, sq_pessoa, sq_tipo_interessado,   envia_email,   tipo_visao)
      values
         (sq_solicitacao_interessado.nextval, p_chave,            p_pessoa,  p_sq_tipo_interessado, p_envia_email, p_tipo_visao);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de interessados da solicitação
      update siw_solicitacao_interessado set
          sq_tipo_interessado = p_sq_tipo_interessado,
          envia_email         = p_envia_email,
          tipo_visao          = p_tipo_visao
      where sq_siw_solicitacao = p_chave
        and sq_pessoa          = p_pessoa;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      DELETE FROM siw_solicitacao_interessado
       where sq_siw_solicitacao = p_chave
         and sq_pessoa          = p_pessoa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;