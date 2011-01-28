create or replace function SP_PutCoPesEnd
   (p_operacao           varchar,
    p_chave              numeric,
    p_pessoa             numeric,
    p_logradouro         varchar,
    p_complemento        varchar,
    p_tipo_endereco      numeric,    
    p_cidade             numeric,    
    p_cep                varchar,
    p_bairro             varchar,
    p_padrao             varchar
   ) returns void as $$
declare
   w_tipo_end            varchar(4000);
   w_sq_pessoa_endereco  numeric(18);
   c_sq_menu             numeric(10);
   c_menu cursor for
     select sq_menu from siw_menu where sq_pessoa = p_pessoa;
begin
   If p_operacao = 'I' Then
      select sq_pessoa_endereco.nextval into w_sq_pessoa_endereco;
      -- Insere registro
      insert into co_pessoa_endereco 
         (sq_pessoa_endereco,         sq_tipo_endereco,     sq_pessoa,     sq_cidade, 
          logradouro,                 complemento,          bairro,        cep, 
          padrao
         )
      (select 
          w_sq_pessoa_endereco, p_tipo_endereco,      p_pessoa,      p_cidade,
          p_logradouro,         p_complemento,        p_bairro,      p_cep,
          p_padrao
       
      );
      select nome into w_tipo_end
        from co_tipo_endereco
       where sq_tipo_endereco = p_tipo_endereco;
      If (w_tipo_end = 'Comercial') Then
         open c_menu;
         loop
            fetch c_menu into c_sq_menu;
            If Not Found Then Exit; End If;
            insert into siw_menu_endereco(sq_menu, sq_pessoa_endereco) 
            values (c_sq_menu, w_sq_pessoa_endereco);
          end loop;
      End If;
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pessoa_endereco set
         sq_tipo_endereco     = p_tipo_endereco,
         logradouro           = p_logradouro,
         cep                  = p_cep,
         bairro               = p_bairro,
         complemento          = p_complemento,
         sq_cidade            = p_cidade,
         padrao               = p_padrao
      where sq_pessoa_endereco = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_pessoa_endereco where sq_pessoa_endereco = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;
