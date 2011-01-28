create or replace FUNCTION sp_putSolic_Vinculo
   (p_operacao             varchar,
    p_chave                numeric,
    p_menu                 numeric  
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em siw_solic_vinculo
      select count(*) into w_existe from siw_solic_vinculo where sq_siw_solicitacao = p_chave and sq_menu = p_menu;
      
      -- Se ainda não existir, insere
      If w_existe = 0 Then
         insert into siw_solic_vinculo (sq_siw_solicitacao, sq_menu) values (p_chave, p_menu);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de siw_solic_vinculo
      DELETE FROM siw_solic_vinculo 
       where sq_siw_solicitacao = p_chave
         and (p_menu      is null or (p_menu is not null and sq_menu      = p_menu));
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;