create or replace FUNCTION SP_PutPD_Reembolso
   (p_cliente                  numeric,
    p_chave                    numeric,
    p_reembolso                varchar,
    p_deposito                 varchar,
    p_valor                    numeric,
    p_observacao               varchar,
    p_financeiro               numeric,
    p_rubrica                  numeric,
    p_lancamento               numeric,
    p_ressarcimento            varchar,
    p_ressarcimento_data       date,
    p_ressarcimento_valor      numeric,
    p_ressarcimento_observacao varchar,
    p_fin_dev                  numeric,
    p_rub_dev                  numeric,
    p_lan_dev                  numeric,
    p_exclui_arquivo           varchar,
    p_caminho                  varchar,
    p_tamanho                  numeric,
    p_tipo                     varchar,
    p_nome_original            varchar   
   ) RETURNS VOID AS $$
DECLARE

   w_financeiro numeric(18) := p_financeiro;
   w_fin_dev    numeric(18) := p_fin_dev;
   w_existe     numeric(18);
   w_arquivo    numeric(18) := null;
   
BEGIN
   -- Recupera o arquivo ligado ao registro
   select sq_arquivo_comprovante into w_arquivo from pd_missao where sq_siw_solicitacao = coalesce(p_chave,0);
    
   -- Verifica se precisa gravar o tipo de vínculo financeiro para reembolso
   If p_financeiro is null and p_lancamento is not null Then
      -- Verifica se há um vínculo único para as opções enviadas
      select count(*) into w_existe
        from pd_vinculo_financeiro
       where sq_projeto_rubrica = p_rubrica
         and sq_tipo_lancamento = p_lancamento
         and bilhete            = 'S';

      -- Prepara variável para gravação se encontrou um, e apenas um registro.
      If w_existe = 1 Then
         select sq_pdvinculo_financeiro into w_financeiro
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rubrica
            and sq_tipo_lancamento = p_lancamento
            and bilhete            = 'S';
      End If;
   End If;
   
   -- Verifica se precisa gravar o tipo de vínculo financeiro para devolucao de valores
   If p_fin_dev is null and p_lan_dev is not null Then
      -- Verifica se há um vínculo único para as opções enviadas
      select count(*) into w_existe
        from pd_vinculo_financeiro
       where sq_projeto_rubrica = p_rubrica
         and sq_tipo_lancamento = p_lancamento
         and ressarcimento      = 'S';

      -- Prepara variável para gravação se encontrou um, e apenas um registro.
      If w_existe = 1 Then
         select sq_pdvinculo_financeiro into w_fin_dev
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rubrica
            and sq_tipo_lancamento = p_lancamento
            and ressarcimento      = 'S';
      End If;
   End If;
   
   -- Atualiza os dados da viagem
   update pd_missao 
      set reembolso                  = coalesce(p_reembolso,'N'),
          reembolso_valor            = coalesce(p_valor,0),
          reembolso_observacao       = p_observacao,
          ressarcimento              = coalesce(p_ressarcimento,'N'),
          ressarcimento_data         = p_ressarcimento_data,
          ressarcimento_valor        = coalesce(p_ressarcimento_valor,0),
          ressarcimento_observacao   = p_ressarcimento_observacao,
          sq_pdvinculo_reembolso     = coalesce(w_financeiro,sq_pdvinculo_reembolso),
          sq_pdvinculo_ressarcimento = coalesce(w_fin_dev,sq_pdvinculo_ressarcimento),
          deposito_identificado      = p_deposito
    where sq_siw_solicitacao = p_chave;

   If p_exclui_arquivo is not null Then -- Remove arquivo
      -- Atualiza os dados da viagem
      update pd_missao set sq_arquivo_comprovante = null where sq_siw_solicitacao = p_chave;

      -- Remove da tabela de arquivos
      DELETE FROM siw_arquivo where sq_siw_arquivo = coalesce(w_arquivo,0);
   Elsif p_caminho is not null Then
      If w_arquivo is null Then -- Inclusão
         -- Recupera a próxima chave
         select sq_siw_arquivo.nextval into w_arquivo from dual;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo
          (sq_siw_arquivo, cliente,   nome,            inclusao, tamanho,   tipo,   caminho,   nome_original,   descricao)
         values
          (w_arquivo,      p_cliente, 'Comprovantes',  now(),  p_tamanho, p_tipo, p_caminho, p_nome_original, 'Arquivo contendo comprovantes de viagem');
          
         -- Atualiza os dados da viagem
         update pd_missao set sq_arquivo_comprovante = w_arquivo where sq_siw_solicitacao = p_chave;
      Else -- Alteração
         update siw_arquivo
            set inclusao      = now(),
                tamanho       = p_tamanho,
                tipo          = p_tipo,
                caminho       = p_caminho,
                nome_original = p_nome_original
         where sq_siw_arquivo = w_arquivo;
      End If;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;