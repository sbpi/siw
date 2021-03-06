alter procedure SP_PutSgPesMod
   (@operacao            varchar(1),
    @chave               int,
    @cliente             int,
    @sq_modulo              int,
    @sq_endereco            int
   ) as
begin
   If @operacao = 'I' begin
      -- Insere registro em SG_PESSOA_MODULO
      insert into sg_pessoa_modulo (sq_pessoa, cliente, sq_modulo, sq_pessoa_endereco)
      (select @chave, @cliente, @sq_modulo, @sq_endereco
       
        where 0 = (select count(*) 
                    from sg_pessoa_modulo 
                   where sq_pessoa          = @chave
                     and cliente            = @cliente
                     and sq_modulo          = @sq_modulo
                     and sq_pessoa_endereco = @sq_endereco
                  )
      );
   end Else if @operacao = 'E' begin
      -- Remove a gestão do módulo  pelo usuário
      delete sg_pessoa_modulo
       where sq_pessoa          = @chave
         and cliente            = @cliente
         and sq_modulo          = @sq_modulo
         and sq_pessoa_endereco = @sq_endereco;
   End 
   
   
end 

