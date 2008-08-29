alter procedure sp_PutSolicInter
   (@p_operacao           varchar(1),
    @p_chave               int =null,
    @p_sq_pessoa           int =null,
    @p_sq_tipo_interessado int =null,
    @p_envia_email  varchar(1) =null,
    @p_tipo_visao          int =null
   ) as
   declare @w_cont int;
begin
   If @p_operacao <> 'I' begin
      -- Se for alteração ou exclusão, faz o tratamento para migração do formato antigo de interessados para o formato novo
      select @w_cont = count(a.sq_solicitacao_interessado)
      from siw_solicitacao_interessado a 
      where a.sq_siw_solicitacao = @p_chave and a.sq_pessoa = @p_sq_pessoa;
     
      -- Se não existe na nova tabela é porquê precisa migrar
      If @w_cont = 0 begin
         If @p_operacao = 'A' begin
            -- Insere registro na nova tabela de interessados
            insert into siw_solicitacao_interessado
               (sq_siw_solicitacao, sq_pessoa,   sq_tipo_interessado,   envia_email,   tipo_visao)
            values
               (@p_chave,            @p_sq_pessoa,    @p_sq_tipo_interessado, @p_envia_email, @p_tipo_visao);
         End
         
         -- Remove das tabelas antigas
         delete gd_demanda_interes where sq_siw_solicitacao = @p_chave and sq_pessoa = @p_sq_pessoa;
         delete pj_projeto_interes where sq_siw_solicitacao = @p_chave and sq_pessoa = @p_sq_pessoa;
      End
   End
   
   If @p_operacao = 'I' begin -- Inclusão
      -- Insere registro na tabela de interessados
      insert into siw_solicitacao_interessado
         (sq_siw_solicitacao, sq_pessoa, sq_tipo_interessado,   envia_email,   tipo_visao)
      values
         (@p_chave,            @p_sq_pessoa,  @p_sq_tipo_interessado, @p_envia_email, @p_tipo_visao);
   end else if @p_operacao = 'A' begin -- Alteração
      -- Atualiza a tabela de interessados da solicitação
      update siw_solicitacao_interessado set
          sq_tipo_interessado = @p_sq_tipo_interessado,
          envia_email         = @p_envia_email,
          tipo_visao          = @p_tipo_visao
      where sq_siw_solicitacao = @p_chave
        and sq_pessoa          = @p_sq_pessoa;
   end else if @p_operacao = 'E' begin -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      delete siw_solicitacao_interessado
       where sq_siw_solicitacao = @p_chave
         and sq_pessoa          = @p_sq_pessoa;
   End
end
