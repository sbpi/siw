create or replace FUNCTION SP_PutTTRamalUsuario
   (p_operacao           varchar,
    p_chave              numeric,
    p_chave_aux          numeric,
    p_chave_aux2         date,
    p_inicio             date,
    p_fim                date 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Inseri registro   
   insert into tt_ramal_usuario
     (sq_ramal, sq_usuario_central, inicio, fim)
     (select p_chave, a.sq_usuario_central, p_inicio, p_fim 
       from tt_usuario a
      where usuario = p_chave_aux
     );
   Elsif p_operacao = 'A' Then
      -- Altera registro
    update tt_ramal_usuario 
       set inicio             = p_inicio,
           fim                = p_fim
     where sq_ramal           = p_chave
       and sq_usuario_central = (select sq_usuario_central from tt_usuario where usuario = p_chave_aux)
       and inicio             = p_chave_aux2;
   Elsif p_operacao = 'F' Then
      -- Altera registro
    update tt_ramal_usuario 
       set fim                = p_fim
     where sq_ramal           = p_chave
       and sq_usuario_central = (select sq_usuario_central from tt_usuario where usuario = p_chave_aux)
       and inicio             = p_chave_aux2;
   Elsif p_operacao = 'E' Then
      -- Exclui registro       
    DELETE FROM tt_ramal_usuario
     where sq_ramal           = p_chave
       and sq_usuario_central = (select sq_usuario_central from tt_usuario where usuario = p_chave_aux)
       and inicio             = p_chave_aux2;
   End If;
 END; $$ LANGUAGE 'PLPGSQL' VOLATILE;