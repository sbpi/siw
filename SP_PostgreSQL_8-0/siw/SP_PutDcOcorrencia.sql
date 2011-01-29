create or replace FUNCTION SP_PutDcOcorrencia
   (p_operacao                  varchar,
    p_sq_esquema                numeric,
    p_cliente                   numeric,
    p_sq_pessoa                 numeric,
    p_data_arquivo              varchar,
    p_arquivo_recebido          varchar,
    p_caminho_recebido          varchar,
    p_tamanho_recebido          varchar,
    p_tipo_recebido             varchar,
    p_arquivo_registro          varchar,
    p_caminho_registro          varchar,
    p_tamanho_registro          varchar,
    p_tipo_registro             varchar,
    p_processados               numeric,
    p_rejeitados                numeric,
    p_nome_recebido             varchar,
    p_nome_registro             varchar  
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave  numeric(18);
   w_chave1 numeric(18);
   w_chave2 numeric(18);
   w_data   date := now();
BEGIN
   If p_operacao = 'I' Then
      -- Recupera a próxima chave da tabela de arquivos
      select nextVal('sq_siw_arquivo') into w_Chave1;
       
      -- Insere o arquivo recebido em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      values
        (w_chave1, p_cliente, p_arquivo_recebido, 
         'Arquivo XML extraído em '||to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi')||'.', 
         w_data, 
         p_tamanho_recebido, 
         p_tipo_recebido, 
         p_caminho_recebido,
         p_nome_recebido
        );

      -- Recupera a próxima chave da tabela de arquivos
      select nextVal('sq_siw_arquivo') into w_Chave2;
       
      -- Insere o arquivo registro em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      values
        (w_chave2, p_cliente, p_arquivo_registro, 
         'Registro da importação do arquivo XML extraído em '||to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi')||'.', 
         w_data, 
         p_tamanho_registro, 
         p_tipo_registro, 
         p_caminho_registro,
         p_nome_registro
        );

      -- Recupera o valor da próxima chave
      select nextVal('sq_orimporta') into w_chave;
      
      -- Insere registro
      insert into dc_ocorrencia 
        (sq_ocorrencia,    sq_esquema,          sq_pessoa,          data_ocorrencia, 
         data_referencia,  processados,         rejeitados,         arquivo_processamento, 
         arquivo_rejeicao
        )
      values
        (w_chave,          p_sq_esquema,        p_sq_pessoa,        now(), 
         to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi'),   p_processados, p_rejeitados,
         w_chave1,        w_chave2
        );
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;