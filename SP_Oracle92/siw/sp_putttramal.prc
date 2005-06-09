create or replace procedure SP_PutTTRamal
   (p_operacao        in varchar2             ,
    p_chave           in number   default null,
    p_sq_central_fone in number               ,
    p_codigo          in varchar2
    ) is
begin
   If p_operacao = 'I' Then
   
   insert into tt_ramal
     (sq_ramal, sq_central_fone, codigo)
     (select sq_ramal.nextVal, p_sq_central_fone, p_codigo from dual);
     
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
       delete tt_ramal
        where sq_ramal = p_chave;
   End If;
end SP_PutTTRamal;
/

