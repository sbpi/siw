create or replace FUNCTION SP_PutCoPesTel
   (p_operacao           varchar,
    p_chave              numeric,
    p_pessoa             numeric,
    p_ddd         varchar,
    p_numero        varchar,
    p_tipo_telefone      numeric,    
    p_cidade             numeric,    
    p_padrao             varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_pessoa_telefone 
         (sq_pessoa_telefone,         sq_tipo_telefone,     sq_pessoa,     sq_cidade, 
          ddd,                        numero,               padrao
         )
      (select 
          sq_pessoa_telefone.nextval, p_tipo_telefone,      p_pessoa,      p_cidade,
          p_ddd,                      p_numero,             p_padrao
        from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pessoa_telefone set
         sq_tipo_telefone     = p_tipo_telefone,
         ddd                  = p_ddd,
         numero               = p_numero,
         sq_cidade            = p_cidade,
         padrao               = p_padrao
      where sq_pessoa_telefone= p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM co_pessoa_telefone where sq_pessoa_telefone = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;