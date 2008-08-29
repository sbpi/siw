alter procedure SP_PutSolicArquivo
   (@p_operacao            varchar(1),
    @p_cliente             int,
    @p_chave               int           =null,
    @p_chave_aux           int           =null,
    @p_nome                varchar(255)  =null,
    @p_descricao           varchar(1000) =null,
    @p_caminho             varchar(255)  =null,
    @p_tamanho             int           =null,
    @p_tipo                varchar(100)  =null,
    @p_nome_original       varchar(255)  =null
   ) as
   declare @w_chave int;
begin
   If @p_operacao = 'I' begin -- Inclusão

      -- Insere registro em siw_ARQUIVO
      insert into siw_arquivo
        (cliente,    nome,    descricao,    inclusao, tamanho,    tipo,    caminho,    nome_original)
      values
        (@p_cliente, @p_nome, @p_descricao, getdate(),  @p_tamanho, @p_tipo, @p_caminho, @p_nome_original);

       set @w_chave = @@IDENTITY        

      -- Insere registro em siw_SOLIC_ARQUIVO
      insert into siw_solic_arquivo
        (sq_siw_solicitacao, sq_siw_arquivo)
      values
        (@p_chave, @w_chave);
   end else if @p_operacao = 'A' begin -- Alteração
      -- Atualiza a tabela de arquivos
      update siw_arquivo
         set nome      = @p_nome,
             descricao = @p_descricao
       where sq_siw_arquivo = @p_chave_aux;
       
      -- Se foi informado um novo arquivo, atualiza os dados
      If @p_caminho is not null begin
         update siw_arquivo
            set inclusao  = getdate(),
                tamanho   = @p_tamanho,
                tipo      = @p_tipo,
                caminho   = @p_caminho,
                nome_original = @p_nome_original
          where sq_siw_arquivo = @p_chave_aux;
      end
   end else if @p_operacao = 'E' begin -- Exclusão
      -- Remove da tabela de vínculo
      delete siw_solic_arquivo where sq_siw_solicitacao = @p_chave and sq_siw_arquivo = @p_chave_aux;
      
      -- Remove da tabela de arquivos
      delete siw_arquivo where sq_siw_arquivo = @p_chave_aux;
   End
end