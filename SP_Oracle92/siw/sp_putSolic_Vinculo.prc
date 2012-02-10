create or replace procedure sp_putSolic_Vinculo
   (p_operacao            in  varchar2,
    p_chave               in  number,
    p_menu                in  number  default null
   ) is
   w_existe number(18);
begin
   If p_operacao = 'I' Then
      -- Verifica se o registro existe em siw_solic_vinculo
      select count(*) into w_existe from siw_solic_vinculo where sq_siw_solicitacao = p_chave and sq_menu = p_menu;
      
      -- Se ainda não existir, insere
      If w_existe = 0 Then
         insert into siw_solic_vinculo (sq_siw_solicitacao, sq_menu) values (p_chave, p_menu);
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove registro de siw_solic_vinculo
      delete siw_solic_vinculo 
       where sq_siw_solicitacao = p_chave
         and (p_menu      is null or (p_menu is not null and sq_menu      = p_menu));
   End If;
end sp_putSolic_Vinculo;
/
