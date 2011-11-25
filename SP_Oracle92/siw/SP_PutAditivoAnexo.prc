create or replace procedure SP_PutAditivoAnexo
   (p_operacao            in  varchar2,
    p_chave               in  number,
    p_chave_aux           in  number,
    p_arquivo             in  number   default null,
    p_nome                in varchar2  default null,
    p_descricao           in varchar2  default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar   default null
   ) is
   w_chave number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo
             (sq_siw_arquivo, cliente,   nome,   descricao,   inclusao, tamanho,   tipo,   caminho,   nome_original)
      (select w_chave,        b.cliente, p_nome, p_descricao, sysdate,  p_tamanho, p_tipo, p_caminho, p_nome_original
         from ac_acordo_aditivo a
              inner join ac_acordo b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
        where a.sq_acordo_aditivo = p_chave_aux
      );
        
      -- Insere registro em ac_aditivo_arquivo
      insert into ac_aditivo_arquivo (sq_acordo_aditivo, sq_siw_arquivo) values (p_chave_aux, w_chave);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de arquivos
      update siw_arquivo
         set nome      = p_nome,
             descricao = p_descricao
       where sq_siw_arquivo = p_arquivo;
       
      -- Se foi informado um novo arquivo, atualiza os dados
      If p_caminho is not null Then
         update siw_arquivo
            set inclusao  = sysdate,
                tamanho   = p_tamanho,
                tipo      = p_tipo,
                caminho   = p_caminho,
                nome_original = p_nome_original
          where sq_siw_arquivo = p_arquivo;
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove da tabela de vínculo
      delete ac_aditivo_arquivo where sq_acordo_aditivo = p_chave_aux and sq_siw_arquivo = p_arquivo;
      
      -- Remove da tabela de arquivos
      delete siw_arquivo where sq_siw_arquivo = p_arquivo;
   End If;
end SP_PutAditivoAnexo;
/
