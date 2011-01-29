create or replace FUNCTION SP_PutEOLocal
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_pessoa_endereco        numeric,
    p_sq_unidade                numeric,
    p_nome                      varchar,
    p_fax                       varchar,
    p_telefone                  varchar,
    p_ramal                     varchar,
    p_telefone2                 varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
     insert into eo_localizacao (sq_localizacao, sq_pessoa_endereco,
                  sq_unidade, nome, fax, telefone, ramal, telefone2, ativo, cliente)         
          (select nextVal('sq_localizacao'),
                  p_sq_pessoa_endereco,
                  p_sq_unidade,                 
                  trim(p_nome),
                  trim(p_fax),
                  trim(p_telefone),
                  trim(p_ramal),
                  trim(p_telefone2),
                  p_ativo,
                  a.sq_pessoa
             from co_pessoa_endereco a
            where sq_pessoa_endereco = p_sq_pessoa_endereco
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_localizacao set
         nome                 = trim(p_nome),
         fax                  = trim(p_fax),
         telefone             = trim(p_telefone),
         ramal                = trim(p_ramal),
         telefone2            = trim(p_telefone2),
         sq_pessoa_endereco   = p_sq_pessoa_endereco,
         sq_unidade           = p_sq_unidade,
         ativo                = p_ativo
      where sq_localizacao    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_localizacao where sq_localizacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;