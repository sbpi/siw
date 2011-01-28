create or replace FUNCTION SP_PutEventoTrigger
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_evento (sq_evento, nome, descricao)
      (select sq_evento.nextval, p_nome, p_descricao from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_evento set
         nome      = p_nome,
         descricao = p_descricao
       where sq_evento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_evento where sq_evento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;