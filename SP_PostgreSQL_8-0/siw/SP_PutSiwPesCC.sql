create or replace function SP_PutSiwPesCC
   (p_operacao    varchar,
    p_pessoa      numeric,
    p_menu        numeric,
    p_cc          numeric
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro em SG_menu_pessoa, para cada endereço que conténha a opção
      insert into siw_pessoa_cc (sq_pessoa, sq_menu, sq_cc) values (p_pessoa, p_menu, p_cc);
   Elsif p_operacao = 'E' Then
      -- Remove a permissão
       delete from siw_pessoa_cc
        where sq_pessoa = p_pessoa
          and sq_menu   = p_menu
          and ((p_cc    is null) or (p_cc is not null and sq_cc = p_cc));
   End If;
end; $$ language 'plpgsql' volatile;
