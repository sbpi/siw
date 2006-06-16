create or replace procedure SP_PutTTUsuarioCentral
   (p_operacao        in varchar2             ,
    p_chave           in number   default null,
    p_cliente         in number   default null,
    p_usuario         in number   default null,
    p_sq_central_fone in number   default null,
    p_codigo          in varchar2 default null
    ) is
begin
   If p_operacao = 'I' Then
   
   insert into tt_usuario (sq_usuario_central, cliente, usuario, sq_central_fone, codigo)
   values (sq_usuario_central.nextVal, p_cliente, p_usuario, p_sq_central_fone, p_codigo);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update tt_usuario set codigo = p_codigo where sq_usuario_central = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete tt_usuario where sq_usuario_central = p_chave;
   End If;
end SP_PutTTUsuarioCentral;
/
