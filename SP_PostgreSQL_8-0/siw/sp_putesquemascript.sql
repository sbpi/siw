create or replace FUNCTION SP_PutEsquemaScript
   (p_operacao             varchar,
    p_cliente             numeric,
    p_sq_esquema_script   numeric,
    p_sq_arquivo          numeric,
    p_sq_esquema          numeric,
    p_nome                varchar,
    p_descricao           varchar,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome_original       varchar,
    p_ordem               numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18);
   w_chave_aux numeric (18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave from dual;
      select sq_esquema_script.nextval into w_chave_aux from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      values
        (w_chave, p_cliente, p_nome, p_descricao, now(), p_tamanho, p_tipo, p_caminho, p_nome_original);
        
      -- Insere registro em DC_ESQUEMA_SCRIPT
      insert into dc_esquema_script
        (sq_esquema_script, sq_esquema, sq_siw_arquivo, ordem)
      values
        (w_chave_aux, p_sq_esquema, w_chave, p_ordem);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de arquivos
      update siw_arquivo
         set nome      = p_nome,
             descricao = p_descricao
       where sq_siw_arquivo = p_sq_arquivo;
      update dc_esquema_script
         set ordem = p_ordem
       where sq_siw_arquivo = p_sq_arquivo;
       
      -- Se foi informado um novo arquivo, atualiza os dados
      If p_caminho is not null Then
         update siw_arquivo
            set inclusao  = now(),
                tamanho   = p_tamanho,
                tipo      = p_tipo,
                caminho   = p_caminho,
                nome_original = p_nome_original
          where sq_siw_arquivo = p_sq_arquivo;
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove da tabela de vínculo
      DELETE FROM dc_esquema_script where sq_esquema = p_sq_esquema and sq_siw_arquivo = p_sq_arquivo;
      
      -- Remove da tabela de arquivos
      DELETE FROM siw_arquivo where sq_siw_arquivo = p_sq_arquivo;
   End If;
  END; $$ LANGUAGE 'PLPGSQL' VOLATILE;