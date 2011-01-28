create or replace FUNCTION SP_PutUorgArquivo
   (p_operacao             varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_nome                varchar,
    p_ordem               numeric,
    p_tipo_arquivo        numeric,
    p_descricao           varchar,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome_original       varchar  
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_Chave;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente,   nome,   descricao,   inclusao, tamanho,    tipo,   caminho,   nome_original,   sq_tipo_arquivo)
      values
        (w_chave,        p_cliente, p_nome, p_descricao, now(),   p_tamanho, p_tipo, p_caminho, p_nome_original, p_tipo_arquivo);
        
      -- Insere registro em eo_unidade_arquivo
      insert into eo_unidade_arquivo
        (sq_unidade, sq_siw_arquivo, ordem)
      values
        (p_chave, w_chave, p_ordem);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de arquivos
      update eo_unidade_arquivo 
        set ordem = p_ordem 
      where sq_unidade = p_chave and sq_siw_arquivo = p_chave_aux;
      update siw_arquivo
         set nome            = p_nome,
             descricao       = p_descricao,
             sq_tipo_arquivo = p_tipo_arquivo
       where sq_siw_arquivo = p_chave_aux;
       
      -- Se foi informado um novo arquivo, atualiza os dados
      If p_caminho is not null Then
         update siw_arquivo
            set inclusao  = now(),
                tamanho   = p_tamanho,
                tipo      = p_tipo,
                caminho   = p_caminho,
                nome_original = p_nome_original
          where sq_siw_arquivo = p_chave_aux;
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove da tabela de vínculo
      DELETE FROM eo_unidade_arquivo where sq_unidade = p_chave and sq_siw_arquivo = p_chave_aux;
      
      -- Remove da tabela de arquivos
      DELETE FROM siw_arquivo where sq_siw_arquivo = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;