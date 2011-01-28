create or replace FUNCTION SP_PutSiwMenEnd
   (p_operacao             varchar,
    p_menu                 numeric,
    p_endereco             numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em SIW_MENU_ENDERECO
      insert into siw_menu_endereco(sq_menu, sq_pessoa_endereco) values (p_menu, p_endereco);
   Elsif p_operacao = 'E' Then
      -- Remove a opção de todos os endereços da organização
      DELETE FROM siw_menu_endereco where sq_menu = p_menu;
   End If;
end; $$ LANGUAGE 'PLPGSQL' VOLATILE;