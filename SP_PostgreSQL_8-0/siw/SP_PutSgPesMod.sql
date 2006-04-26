create or replace function SP_PutSgPesMod
   (p_operacao            varchar,
    p_chave               numeric,
    p_cliente             numeric,
    p_modulo              numeric,
    p_endereco            numeric
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro em SG_PESSOA_MODULO
      insert into sg_pessoa_modulo (sq_pessoa, cliente, sq_modulo, sq_pessoa_endereco)
           values (p_Chave, p_cliente, p_modulo, p_endereco);
   Elsif p_operacao = 'E' Then
      -- Remove a gestão do módulo  pelo usuário
      delete from sg_pessoa_modulo
       where sq_pessoa          = p_chave
         and cliente            = p_cliente
         and sq_modulo          = p_modulo
         and sq_pessoa_endereco = p_endereco;
   End If;
end; $$ language 'plpgsql' volatile;
