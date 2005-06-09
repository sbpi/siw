create or replace procedure SP_PutTTTronco
   (p_operacao           in varchar2,
    p_chave              in number   default null,
    p_cliente            in number   default null,
    p_sq_central_fone    in number   default null,
    p_sq_pessoa_telefone in number   default null,
    p_codigo             in varchar2 default null,
    p_ativo              in varchar2 default null
    ) is
begin
   If p_operacao = 'I' Then
   
   insert into tt_tronco
     (sq_tronco, cliente, sq_central_fone, sq_pessoa_telefone, codigo, ativo)
     (select sq_tronco.nextVal, p_cliente, p_sq_central_fone, p_sq_pessoa_telefone, p_codigo, p_ativo from dual);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update tt_tronco
      set sq_tronco          = p_chave,
          cliente            = p_cliente,
          sq_central_fone    = p_sq_central_fone,
          sq_pessoa_telefone = p_sq_pessoa_telefone,
          codigo             = p_codigo,
          ativo              = p_ativo
        where sq_tronco = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete tt_tronco
        where sq_tronco = p_chave;
   End If;
end SP_PutTTTronco;
/

