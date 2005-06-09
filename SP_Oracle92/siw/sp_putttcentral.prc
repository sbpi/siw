create or replace procedure SP_PutTTCentral
   (p_operacao           in varchar2             ,
    p_chave              in number   default null,
    p_cliente            in number   default null,
    p_sq_pessoa_endereco in number,
    p_arquivo_bilhetes   in varchar2 default null,
    p_recupera_bilhetes  in varchar2 default null
    ) is
begin
   If p_operacao = 'I' Then
   
   insert into tt_central
     (sq_central_fone, cliente, sq_pessoa_endereco, arquivo_bilhetes, recupera_bilhetes)
     (select sq_central_telefonica.nextVal, p_cliente, p_sq_pessoa_endereco, p_arquivo_bilhetes, p_recupera_bilhetes from dual);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update tt_central
       set 
       sq_central_fone    = p_chave,
       cliente            = p_cliente,
       sq_pessoa_endereco = p_sq_pessoa_endereco,
       arquivo_bilhetes   = p_arquivo_bilhetes,
       recupera_bilhetes  = p_recupera_bilhetes
       where sq_central_fone = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete tt_central
        where sq_central_fone = p_chave;
   End If;
end SP_PutTTCentral;
/

