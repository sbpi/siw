create or replace FUNCTION sp_PutNatureza_PE
   (p_operacao  varchar,
    p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_ativo     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pe_natureza (sq_penatureza, cliente, nome, ativo)
      (select sq_penatureza.nextval, p_cliente, p_nome,  p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pe_natureza
         set cliente     = p_cliente,
             nome        = p_nome,
             ativo       = p_ativo
       where sq_penatureza = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pe_natureza
       where sq_penatureza = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;