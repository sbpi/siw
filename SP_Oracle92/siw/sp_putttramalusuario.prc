create or replace procedure SP_PutTTRamalUsuario
   (p_operacao           in varchar2,
    p_chave              in number,
    p_chave_aux          in number,
    p_chave_aux2         in date,
    p_inicio             in date   default null,
    p_fim                in date default null
   ) is
begin
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
    delete tt_ramal_usuario
     where sq_ramal           = p_chave
       and sq_usuario_central = (select sq_usuario_central from tt_usuario where usuario = p_chave_aux)
       and inicio             = p_chave_aux2;
   End If;
  end SP_PutTTRamalUsuario;
/

