create or replace FUNCTION SP_PutPD_Alteracao
   (p_operacao             varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_bilhete_tarifa      numeric,
    p_bilhete_taxa        numeric,
    p_hospedagem_valor    numeric,
    p_diaria_valor        numeric,
    p_justificativa       varchar,
    p_autorizacao_pessoa  numeric,
    p_autorizacao_cargo   varchar,
    p_autorizacao_data    date,
    p_exclui_arquivo      varchar,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome_original       varchar   
   ) RETURNS VOID AS $$
DECLARE
   w_chave_aux numeric(18)    := p_chave_aux;
   w_arquivo   numeric(18)    := null;
BEGIN
   If p_operacao = 'A' or p_operacao = 'E' Then
      -- Recupera o arquivo ligado ao registro
      select sq_siw_arquivo into w_arquivo from pd_alteracao where sq_pdalteracao = coalesce(w_chave_aux,0);
   End If;
    
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_pdalteracao.nextval into w_chave_aux;
      
      -- Insere registro na tabela de alterações
      insert into pd_alteracao
        (sq_pdalteracao,    sq_siw_solicitacao,    diaria_moeda,      diaria_valor,         hospedagem_moeda,    hospedagem_valor, 
         bilhete_tarifa,    bilhete_taxa,          justificativa,     autorizacao_pessoa,   autorizacao_cargo,   autorizacao_data,
         inclusao)
      (select
         w_chave_aux,       p_chave,               a.sq_moeda,        p_diaria_valor,       a.sq_moeda,          p_hospedagem_valor, 
         p_bilhete_tarifa,  p_bilhete_taxa,        p_justificativa,   p_autorizacao_pessoa, p_autorizacao_cargo, p_autorizacao_data, 
         now()
       from co_moeda a
       where a.sigla = 'BRL'
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de alterações
      update pd_alteracao
         set diaria_valor       = p_diaria_valor,
             hospedagem_valor   = p_hospedagem_valor,
             bilhete_tarifa     = p_bilhete_tarifa,
             bilhete_taxa       = p_bilhete_taxa,
             justificativa      = p_justificativa,
             autorizacao_pessoa = p_autorizacao_pessoa,
             autorizacao_cargo  = p_autorizacao_cargo,
             autorizacao_data   = p_autorizacao_data,
             ultima_alteracao   = now()
       where sq_pdalteracao = w_chave_aux;
      
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de alterações de viagem
      DELETE FROM PD_Alteracao where sq_pdalteracao = w_chave_aux;
   End If;

   If p_exclui_arquivo is not null or p_operacao = 'E' Then -- Remove arquivo
      -- Atualiza os dados da alteração de viagem
      update pd_alteracao set sq_siw_arquivo = null where sq_pdalteracao = w_chave_aux;

      -- Remove da tabela de arquivos
      DELETE FROM siw_arquivo where sq_siw_arquivo = coalesce(w_arquivo,0);
   Elsif p_caminho is not null Then
      If w_arquivo is null Then -- Inclusão
         -- Recupera a próxima chave
         select sq_siw_arquivo.nextval into w_arquivo;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo
          (sq_siw_arquivo, cliente,   nome,            inclusao, tamanho,   tipo,   caminho,   nome_original,   descricao)
         values
          (w_arquivo,      p_cliente, 'Justificativa', now(),  p_tamanho, p_tipo, p_caminho, p_nome_original, 'Justificativa para alteração de viagem');
          
         -- Atualiza os dados da viagem
         update pd_alteracao set sq_siw_arquivo = w_arquivo where sq_siw_solicitacao = p_chave;
      Else -- Alteração
         -- Recupera o arquivo ligado ao registro
         select sq_siw_arquivo into w_arquivo from pd_alteracao where sq_pdalteracao = w_chave_aux;
         
         update siw_arquivo
            set inclusao      = now(),
                tamanho       = p_tamanho,
                tipo          = p_tipo,
                caminho       = p_caminho,
                nome_original = p_nome_original
         where sq_siw_arquivo = w_arquivo;
      End If;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;