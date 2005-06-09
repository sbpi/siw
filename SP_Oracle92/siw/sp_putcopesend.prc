create or replace procedure SP_PutCoPesEnd
   (p_operacao          in  varchar2,
    p_chave             in  number   default null,
    p_pessoa            in  number,
    p_logradouro        in  varchar2,
    p_complemento       in  varchar2 default null,
    p_tipo_endereco     in  number,    
    p_cidade            in  number,    
    p_cep               in  varchar2 default null,
    p_bairro            in  varchar2 default null,
    p_padrao            in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_pessoa_endereco 
         (sq_pessoa_endereco,         sq_tipo_endereco,     sq_pessoa,     sq_cidade, 
          logradouro,                 complemento,          bairro,        cep, 
          padrao
         )
      (select 
          sq_pessoa_endereco.nextval, p_tipo_endereco,      p_pessoa,      p_cidade,
          p_logradouro,               p_complemento,        p_bairro,      p_cep,
          p_padrao
        from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pessoa_endereco set
         sq_tipo_endereco     = p_tipo_endereco,
         logradouro           = p_logradouro,
         cep                  = p_cep,
         bairro               = p_bairro,
         complemento          = p_complemento,
         sq_cidade            = p_cidade,
         padrao               = p_padrao
      where sq_pessoa_endereco= p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_pessoa_endereco where sq_pessoa_endereco = p_chave;
   End If;
end SP_PutCoPesEnd;
/

