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
      -- Se for altera��o ou exclus�o, faz o tratamento para migra��o do formato antigo de interessados para o formato novo
      select @w_cont = count(a.sq_solicitacao_interessado)
      from siw_solicitacao_interessado a 
      where a.sq_siw_solicitacao = @p_chave and a.sq_pessoa = @p_sq_pessoa;
     
      -- Se n�o existe na nova tabela � porqu� precisa migrar
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
   
   If @p_operacao = 'I' begin -- Inclus�o
      -- Insere registro na tabela de interessados
      insert into siw_solicitacao_interessado
         (sq_siw_solicitacao, sq_pessoa, sq_tipo_interessado,   envia_email,   tipo_visao)
      values
         (@p_chave,            @p_sq_pessoa,  @p_sq_tipo_interessado, @p_envia_email, @p_tipo_visao);
   end else if @p_operacao = 'A' begin -- Altera��o
      -- Atualiza a tabela de interessados da solicita��o
      update siw_solicitacao_interessado set
          sq_tipo_interessado = @p_sq_tipo_interessado,
          envia_email         = @p_envia_email,
          tipo_visao          = @p_tipo_visao
      where sq_siw_solicitacao = @p_chave
        and sq_pessoa          = @p_sq_pessoa;
   end else if @p_operacao = 'E' begin -- Exclus�o
      -- Remove o registro na tabela de interessados da solicita��o
      delete siw_solicitacao_interessado
       where sq_siw_solicitacao = @p_chave
         and sq_pessoa          = @p_sq_pessoa;
   End
end
