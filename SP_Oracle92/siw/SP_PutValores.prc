create or replace procedure SP_PutValores
   (p_operacao                 in  varchar2,
    p_cliente                  in  number   default null,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_tipo                     in  varchar2 default null,
    p_codigo_externo           in  varchar2 default null,    
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro      
      insert into fn_valores
        (sq_valores, cliente, nome, tipo, codigo_externo, ativo)
      values
        (sq_valores.nextval, p_cliente, p_nome, p_tipo, p_codigo_externo, p_ativo);
      
   Elsif p_operacao = 'A' Then
      -- Altera registro      
      update fn_valores
         set cliente        = p_cliente,
             nome           = p_nome,
             tipo           = p_tipo,
             codigo_externo = p_codigo_externo,
             ativo          = p_ativo
       where sq_valores     = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
   delete fn_valores where sq_valores = p_chave;
   End If;
end SP_PutValores;
/
