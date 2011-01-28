create or replace FUNCTION SP_PutDataEspecial
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_sq_pais                   numeric,
    p_co_uf                     varchar,
    p_sq_cidade                 numeric,
    p_tipo                      varchar,
    p_data_especial             varchar,
    p_nome                      varchar,
    p_abrangencia               varchar,
    p_expediente                varchar,    
    p_ativo                     varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      -- Insere registro
      insert into eo_data_especial
        (sq_data_especial, cliente, sq_pais, co_uf, sq_cidade, tipo, data_especial, nome, abrangencia, expediente, ativo)
      values
        (sq_data_especial.nextval, p_cliente, p_sq_pais, p_co_uf, p_sq_cidade, p_tipo, p_data_especial, trim(p_nome), p_abrangencia, p_expediente, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_data_especial
         set sq_pais       = p_sq_pais,
             co_uf         = p_co_uf,
             sq_cidade     = p_sq_cidade,
             tipo          = p_tipo,
             data_especial = p_data_especial,
             nome          = trim(p_nome),
             abrangencia   = p_abrangencia,
             expediente    = p_expediente,
             ativo = p_ativo
       where sq_data_especial = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_data_especial where sq_data_especial = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;