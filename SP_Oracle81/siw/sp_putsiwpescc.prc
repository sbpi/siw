create or replace procedure SP_PutSiwPesCC
   (p_operacao    in  varchar2,
    p_pessoa      in  number,
    p_menu        in  number,
    p_cc          in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro em SG_menu_pessoa, para cada endereço que conténha a opção
      insert into siw_pessoa_cc (sq_pessoa, sq_menu, sq_cc) values (p_pessoa, p_menu, p_cc);
   Elsif p_operacao = 'E' Then
      -- Remove a permissão
       delete siw_pessoa_cc
        where sq_pessoa = p_pessoa
          and sq_menu   = p_menu
          and ((p_cc    is null) or (p_cc is not null and sq_cc = p_cc));
   End If;
   commit;   
end SP_PutSiwPesCC;
/

