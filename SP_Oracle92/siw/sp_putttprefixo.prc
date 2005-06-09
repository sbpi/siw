create or replace procedure SP_PutTTPrefixo
   (p_operacao   in  varchar2,
    p_chave      in  number    default null,
    p_prefixo    in  varchar2  default null,
    p_localidade in  varchar2  default null,
    p_sigla      in  varchar2  default null,
    p_uf         in  varchar2  default null,
    p_ddd        in  varchar2  default null,
    p_controle    in  varchar2  default null,
    p_degrau     in  varchar2  default null
    ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
     insert into tt_prefixos 
       (sq_prefixo, prefixo, localidade, sigla, uf, ddd, controle, degrau)                       
       (select sq_prefixo.nextVal, p_prefixo, p_localidade, p_sigla, p_uf, p_ddd, p_controle, p_degrau from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update tt_prefixos
         set sq_prefixo = p_chave,
             prefixo = p_prefixo,
             localidade = p_localidade,
             sigla = p_sigla,
             uf = p_uf,
             ddd = p_ddd,
             controle = p_controle,
             degrau = p_degrau
       where sq_prefixo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete tt_prefixos
      where sq_prefixo = p_chave;
   End If;
end SP_PutTTPrefixo;
/

