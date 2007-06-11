create or replace procedure SP_GetDeskTop
   (p_cliente   in  number,
    p_usuario   in number,
    p_ano       in number,
    p_result    out sys_refcursor
   ) is
   w_interno  varchar2(1);
begin
   -- Verifica se o vínculo do usuário com a organização é interno ou externo
   select case when count(*) > 0 then 'S' else 'N' end into w_interno
     from co_pessoa                  a 
          inner join co_tipo_vinculo b on (a.sq_tipo_vinculo = b.sq_tipo_vinculo and
                                           b.interno         = 'S'
                                          ) 
         where a.sq_pessoa = p_usuario;
   
   If w_interno = 'S' Then
      -- Recupera a lista de solicitações da mesa de trabalho do usuário
      open p_result for
         select v.sq_menu, v.sq_pessoa, w.sq_modulo, w.nome nm_modulo, w.sigla sg_modulo, v.sq_menu, v.nome nm_servico, 
                v.link, v.imagem, v.p1, v.p2, v.p3, v.p4, v.sigla sg_servico, x.qtd, y.qtd qtd_solic
         from siw_menu              v
              inner join siw_modulo w on (v.sq_modulo = w.sq_modulo)
              left  join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) qtd 
                            FROM siw_tramite                    e
                                 inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                                   inner   join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                                   from siw_solicitacao
                                                )               f on (d.sq_siw_solicitacao = f.sq_siw_solicitacao)
                                   inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                                      c.tramite        = 'S' and
                                                                      c.ativo          = 'S' and
                                                                      c.sq_pessoa      = p_cliente
                                                                     ) 
                                     inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
                           where (e.ativo = 'S' or (e.sigla = 'AT' and d.solicitante = p_usuario and c.consulta_opiniao = 'S' and d.opiniao is null))
                             and ((c.sigla <> 'PJCAD' and (c.destinatario = 'S' and d.executor = p_usuario) or (c.destinatario = 'N' and f.acesso > 15)) or
                                  (C.sigla =  'PJCAD' and coalesce(e.sigla,'---') <> 'CI' and f.acesso >= 8)
                                 )
                             and 'CI'    <> Nvl(e.sigla,'nulo')
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          x on (v.sq_menu = x.sq_menu)
              inner join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) qtd 
                            FROM siw_tramite                      e
                                 inner     join siw_menu          c on (e.sq_menu        = c.sq_menu) 
                                   inner   join siw_modulo        b on (c.sq_modulo      = b.sq_modulo)
                                   inner   join siw_solicitacao   d on (e.sq_siw_tramite = d.sq_siw_tramite and
                                                                        (('N'            = c.consulta_opiniao and d.conclusao is null) or
                                                                         ('S'            = c.consulta_opiniao and d.opiniao is null)
                                                                        )
                                                                       )
                                     inner join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                                   from siw_solicitacao
                                                )                 f on (d.sq_siw_solicitacao = f.sq_siw_solicitacao)
                           where c.tramite       = 'S' 
                             and (e.ativo = 'S' or (e.sigla = 'AT' and d.solicitante = p_usuario and c.consulta_opiniao = 'S' and d.opiniao is null))
                             and c.sq_pessoa     = p_cliente
                             and f.acesso        > 0
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          y on (v.sq_menu = y.sq_menu)
           where v.tramite   = 'S' 
             and v.ativo     = 'S' 
             and v.sq_pessoa = p_cliente
          order by nm_modulo, nm_servico;
   Else
      -- Recupera a lista de solicitações da mesa de trabalho do usuário
      open p_result for
         select v.sq_menu, v.sq_pessoa, w.sq_modulo, w.nome nm_modulo, w.sigla sg_modulo, v.sq_menu, v.nome nm_servico, 
                v.link, v.imagem, v.p1, v.p2, v.p3, v.p4, v.sigla sg_servico, x.qtd
         from siw_menu              v
              inner join siw_modulo w on (v.sq_modulo = w.sq_modulo)
              inner join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) qtd 
                            FROM siw_tramite                    e
                                 inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                                   inner   join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                                   from siw_solicitacao
                                                )               f on (d.sq_siw_solicitacao = f.sq_siw_solicitacao)
                                   inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                                      c.tramite        = 'S' and
                                                                      c.ativo          = 'S' and
                                                                      c.sq_pessoa      = p_cliente
                                                                     ) 
                                     inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
                           where e.ativo  = 'S'
                             and f.acesso > 0
                             and 'CI'     <> Nvl(e.sigla,'nulo')
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          x on (v.sq_menu = x.sq_menu)
           where v.tramite   = 'S' 
             and v.ativo     = 'S' 
             and v.sq_pessoa = p_cliente
          order by nm_modulo, nm_servico;
   End If;
end SP_GetDeskTop;
/
