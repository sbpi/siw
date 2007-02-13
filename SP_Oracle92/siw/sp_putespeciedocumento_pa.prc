create or replace procedure sp_PutEspecieDocumento_PA
   (p_operacao  in  varchar2             ,
    p_chave     in  number   default null,
    p_cliente   in  number   default null,
    p_nome      in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_ativo     in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_especie_documento (sq_especie_documento, cliente, nome, sigla, ativo)
      (select sq_especie_documento.nextval, p_cliente, p_nome, p_sigla, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_especie_documento
         set 
             cliente     = p_cliente,
             nome        = p_nome,
             sigla       = p_sigla,
             ativo       = p_ativo
       where sq_especie_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_especie_documento
       where sq_especie_documento = p_chave;
   End If;
end sp_PutEspecieDocumento_PA;
/
