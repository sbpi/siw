create or replace FUNCTION SP_PutTTRamal
   (p_operacao        varchar,
    p_chave           numeric,
    p_sq_central_fone numeric,
    p_codigo          varchar
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
   
   insert into tt_ramal
     (sq_ramal, sq_central_fone, codigo)
     (select sq_ramal.nextVal, p_sq_central_fone, p_codigo);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update tt_ramal
       set 
       sq_ramal        = p_chave,
       sq_central_fone = p_sq_central_fone,
       codigo          = p_codigo
       where sq_ramal  = p_chave;
   Elsif p_operacao    = 'E' Then
      -- Exclui registro
       DELETE FROM tt_ramal
        where sq_ramal = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;