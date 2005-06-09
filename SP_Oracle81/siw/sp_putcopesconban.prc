create or replace procedure SP_PutCoPesConBan
   (p_operacao          in  varchar2,
    p_chave             in  number   default null,
    p_pessoa            in  number,
    p_agencia           in  number,    
    p_oper              in  varchar2,
    p_numero            in  varchar2 default null,
    p_tipo_conta        in  number,    
    p_ativo             in  varchar2,
    p_padrao            in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_pessoa_conta
         (sq_pessoa_conta,                  operacao,              sq_pessoa,      sq_agencia, 
          numero,                           ativo,                 padrao,         tipo_conta
         )
      (select 
          sq_pessoa_conta_bancaria.nextval, p_oper,                p_pessoa,       p_agencia,
          p_numero,                         p_ativo,               p_padrao,       p_tipo_conta
        from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pessoa_conta set
         operacao             = p_oper,
         tipo_conta           = p_tipo_conta,
         ativo                = p_ativo,
         padrao               = p_padrao
      where sq_pessoa_conta   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_pessoa_conta where sq_pessoa_conta = p_chave;
   End If;
end SP_PutCoPesConBan;
/

