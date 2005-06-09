create or replace procedure SP_GetMenuData
   (p_sq_menu   in  number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados de uma opção do menu
   open p_result for
      select a.*, b.solicita_cc, b.envia_mail mail_tramite, c.sigla sg_modulo, e.sq_cidade
      from siw_menu                               a,
           siw_tramite        b,
           eo_unidade         d,
           co_pessoa_endereco e,
           siw_modulo         c
      where (a.sq_modulo          = c.sq_modulo)
        and (a.sq_menu            = b.sq_menu (+) and
             b.sigla (+)          = 'CI'
            )
        and (a.sq_unid_executora  = d.sq_unidade (+))
        and (d.sq_pessoa_endereco = e.sq_pessoa_endereco (+))
        and a.sq_menu   = p_sq_menu;
end SP_GetMenuData;
/

