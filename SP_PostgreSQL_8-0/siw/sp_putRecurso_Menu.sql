create or replace FUNCTION sp_putRecurso_Menu
   (p_operacao             varchar,
    p_chave                numeric,
    p_menu                 numeric  
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em eo_recurso_menu
      select count(*) into w_existe from eo_recurso_menu where sq_recurso = p_chave and sq_menu = p_menu;
      
      -- Se ainda não existir, insere
      If w_existe = 0 Then
         insert into eo_recurso_menu (sq_recurso, sq_menu) values (p_chave, p_menu);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de eo_recurso_menu
      DELETE FROM eo_recurso_menu 
       where sq_recurso = p_chave
         and (p_menu      is null or (p_menu is not null and sq_menu      = p_menu));
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;