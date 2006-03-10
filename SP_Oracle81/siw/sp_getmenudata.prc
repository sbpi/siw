create or replace procedure SP_GetMenuData
   (p_sq_menu   in  number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados de uma opção do menu
   open p_result for
      select a.sq_menu, a.sq_modulo, a.sq_pessoa, a.sq_menu_pai, a.nome, a.link, a.tramite, a.ordem, a.ultimo_nivel, 
             a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem, a.descentralizado, a.externo, a.target, a.ativo, a.acesso_geral, 
             a.como_funciona, a.acompanha_fases, a.sq_unid_executora, a.finalidade, a.arquivo_proced, a.emite_os, 
             a.consulta_opiniao, a.envia_email, a.exibe_relatorio, a.vinculacao, a.data_hora, a.envia_dia_util, a.descricao, 
             a.justificativa, a.destinatario, a.controla_ano, a.libera_edicao, 
             b.solicita_cc, b.envia_mail mail_tramite, c.sigla sg_modulo, e.sq_cidade
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
