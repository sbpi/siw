create or replace procedure SP_PutOrImport
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_sq_pessoa                in  number    default null,
    p_data_arquivo             in  varchar2  default null,
    p_arquivo_recebido         in  varchar2  default null,
    p_caminho_recebido         in  varchar2  default null,
    p_tamanho_recebido         in  varchar2  default null,
    p_tipo_recebido            in  varchar2  default null,
    p_arquivo_registro         in  varchar2  default null,
    p_caminho_registro         in  varchar2  default null,
    p_tamanho_registro         in  varchar2  default null,
    p_tipo_registro            in  varchar2  default null,
    p_registros                in  number    default null,
    p_importados               in  number    default null,
    p_rejeitados               in  number    default null,
    p_situacao                 in  number    default null,
    p_nome_recebido            in  varchar2  default null,
    p_nome_registro            in  varchar2  default null
   ) is
   
   w_chave  number(18);
   w_chave1 number(18);
   w_chave2 number(18);
   w_data   date := sysdate;
begin
   If p_operacao = 'I' Then
      -- Recupera a pr�xima chave da tabela de arquivos
      select sq_siw_arquivo.nextval into w_Chave1 from dual;
       
      -- Insere o arquivo recebido em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      values
        (w_chave1, p_cliente, p_arquivo_recebido, 
         'Arquivo SIAFI extra�do em '||to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi')||'.', 
         w_data, 
         p_tamanho_recebido, 
         p_tipo_recebido, 
         p_caminho_recebido,
         p_nome_recebido
        );

      -- Recupera a pr�xima chave da tabela de arquivos
      select sq_siw_arquivo.nextval into w_Chave2 from dual;
       
      -- Insere o arquivo registro em SIW_ARQUIVO
      insert into siw_arquivo
        (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      values
        (w_chave2, p_cliente, p_arquivo_registro, 
         'Registro da importa��o do arquivo SIAFI extra�do em '||to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi')||'.', 
         w_data, 
         p_tamanho_registro, 
         p_tipo_registro, 
         p_caminho_registro,
         p_nome_registro
        );

      -- Recupera o valor da pr�xima chave
      select sq_orimporta.nextval into w_chave from dual;
      
      -- Insere registro
      insert into or_importacao
        (sq_orimporta,    cliente,             data,               sq_pessoa, 
         data_arquivo,    arquivo_recebido,    arquivo_registro,   registros, 
         importados,      rejeitados,          situacao
        )
      values
        (w_chave,         p_cliente,           w_data,             p_sq_pessoa, 
         to_date(p_data_arquivo,'dd/mm/yyyy, hh24:mi'),  
         w_chave1,        w_chave2,            p_registros, 
         p_importados,    p_rejeitados,        p_situacao
        );
   End If;
end SP_PutOrImport;
/
