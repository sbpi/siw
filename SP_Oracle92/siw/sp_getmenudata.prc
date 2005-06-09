create or replace procedure SP_GetMenuData
   (p_sq_menu   in  number,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera os dados de uma opção do menu
   open p_result for
      select a.*, b.solicita_cc, b.envia_mail mail_tramite, c.sigla sg_modulo, e.sq_cidade
      from siw_menu                               a
             left outer   join siw_tramite        b on (a.sq_menu            = b.sq_menu and 
                                                        b.sigla = 'CI'
                                                       )
             left outer   join eo_unidade         d on (a.sq_unid_executora  = d.sq_unidade)
               left outer join co_pessoa_endereco e on (d.sq_pessoa_endereco = e.sq_pessoa_endereco)
             inner        join siw_modulo         c on (a.sq_modulo          = c.sq_modulo)
      where a.sq_menu   = p_sq_menu;
end SP_GetMenuData;
/

