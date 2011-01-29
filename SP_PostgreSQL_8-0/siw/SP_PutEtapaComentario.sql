create or replace FUNCTION SP_PutEtapaComentario
   (p_operacao             varchar,
    p_chave                numeric,
    p_chave_aux            numeric,
    p_pessoa               numeric,
    p_comentario          varchar,
    p_mail                varchar,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome                varchar,
    p_remove              varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave         numeric(18) := p_chave_aux;
   w_chave_arq     numeric(18) := null;
   w_arq           varchar(4000) := ', ';
   w_existe        numeric(18);

    c_arquivos CURSOR FOR
      select sq_siw_arquivo from pj_comentario_arq where sq_etapa_comentario = p_chave_aux;
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_etapa_comentario') into w_chave;
      
      -- Insere registro na tabela de comentários de etapa
      insert into pj_etapa_comentario
        (sq_etapa_comentario, sq_projeto_etapa, sq_pessoa_inclusao, comentario,   inclusao, envia_mail, registrado, registro)
      values
        (w_chave,             p_chave,          p_pessoa,           p_comentario, now(),  'N',        'N',        null);

      -- Se foi informado um arquivo, grava.
      If p_caminho is not null Then
         -- Recupera a próxima chave
         select nextVal('sq_siw_arquivo') into w_chave_arq;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
         (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, now(), 
                 p_tamanho,   p_tipo,        p_caminho, p_nome
            from co_pessoa a
           where a.sq_pessoa = p_pessoa
         );
         
         -- Insere registro em PJ_COMENTARIO_ARQ
         insert into pj_comentario_arq (sq_etapa_comentario, sq_siw_arquivo)
         values (w_chave, w_chave_arq);
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de comentários
      update pj_etapa_comentario
         set comentario          = p_comentario
       where sq_etapa_comentario = w_chave;

      -- Se foi informado um novo arquivo, atualiza os dados
      If p_caminho is not null Then
         select count(*) into w_existe from pj_comentario_arq where sq_etapa_comentario = w_chave;
         
         If w_existe = 0 Then -- Inclui o anexo
            -- Recupera a próxima chave
            select nextVal('sq_siw_arquivo') into w_chave_arq;
           
            -- Insere registro em SIW_ARQUIVO
            insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
            (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, now(), 
                    p_tamanho,   p_tipo,        p_caminho, p_nome
               from co_pessoa a
              where a.sq_pessoa = p_pessoa
            );
           
            -- Insere registro em PJ_COMENTARIO_ARQ
            insert into pj_comentario_arq (sq_etapa_comentario, sq_siw_arquivo)
            values (w_chave, w_chave_arq);
         Else
             -- Recupera a chave do arquivo ligado ao comentário
             select sq_siw_arquivo into w_chave_arq from pj_comentario_arq where sq_etapa_comentario = w_chave;

             -- Altera dados do arquivo
             update siw_arquivo
                set inclusao      = now(),
                    tamanho       = p_tamanho,
                    tipo          = p_tipo,
                    caminho       = p_caminho,
                    nome_original = p_nome
              where sq_siw_arquivo = w_chave_arq;
         End If;
      End If;
      
      -- Opção para remover os anexos do comentário
      If p_remove is not null Then
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
  
         -- Remove da tabela de vínculo
         DELETE FROM pj_comentario_arq where sq_etapa_comentario = w_chave;

         -- Remove da tabela de arquivos
         DELETE FROM siw_arquivo where sq_siw_arquivo in (w_arq);
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Monta string com a chave dos arquivos ligados à solicitação informada
      for crec in c_arquivos loop
         w_arq := w_arq || crec.sq_siw_arquivo;
      end loop;
      w_arq := substr(w_arq, 3, length(w_arq));

      -- Remove da tabela de vínculo
      DELETE FROM pj_comentario_arq where sq_etapa_comentario = w_chave;
      
      -- Remove da tabela de arquivos
      DELETE FROM siw_arquivo where sq_siw_arquivo in (w_arq);

      -- Remove o registro na tabela de etapas do projeto
      DELETE FROM pj_etapa_comentario
       where sq_etapa_comentario   = w_chave;

   Elsif p_operacao = 'V' Then -- Registro
      -- Atualiza a tabela de comentários
      update pj_etapa_comentario
         set registrado = 'S',
             registro   = now(),
             envia_mail = nvl(p_mail,'N')
       where sq_etapa_comentario = w_chave;

   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;