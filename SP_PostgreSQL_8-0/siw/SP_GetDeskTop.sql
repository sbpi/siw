CREATE OR REPLACE FUNCTION siw.SP_GetDeskTop
   (p_cliente   numeric,
    p_usuario   numeric,
    p_ano       numeric,
    p_result    refcursor
   )

  RETURNS refcursor AS
$BODY$
declare
    w_interno  varchar;
begin
   -- Verifica se o v�nculo do usu�rio com a organiza��o � interno ou externo
   select case when count(*) > 0 then 'S' else 'N' end into w_interno
     from co_pessoa                  a 
          inner join co_tipo_vinculo b on (a.sq_tipo_vinculo = b.sq_tipo_vinculo and
                                           b.interno         = 'S'
                                          ) 
         where a.sq_pessoa = p_usuario;
   
   If w_interno = 'S' Then
      -- Recupera a lista de solicita��es da mesa de trabalho do usu�rio
      open p_result for
         select v.sq_menu, v.sq_pessoa, w.sq_modulo, w.ordem as or_modulo, w.nome as nm_modulo, w.sigla as sg_modulo, 
                v.sq_menu, v.nome as nm_servico, 
                v.link, v.imagem, v.p1, v.p2, v.p3, v.p4, v.sigla as sg_servico, x.qtd, y.qtd as qtd_solic
         from siw_menu              v
              inner join siw_modulo w on (v.sq_modulo = w.sq_modulo)
              left  join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) as qtd 
                            FROM siw_tramite                    e
                                 inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                                   inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                                      c.tramite        = 'S' and
                                                                      c.ativo          = 'S' and
                                                                      c.sq_pessoa      = p_cliente
                                                                     ) 
                                     inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
                           where b.sigla <> 'PA' -- O m�dulo de protocolo n�o tem interven��es pela mesa de trabalho
                             and (e.ativo = 'S' or (e.sigla = 'AT' and d.solicitante = p_usuario and c.consulta_opiniao = 'S' and d.opiniao is null))
                             and ((c.sigla <> 'PJCAD' and (c.destinatario = 'S' and d.executor = p_usuario) or (c.destinatario = 'N' and acesso(d.sq_siw_solicitacao, p_usuario) > 15)) or
                                  (C.sigla =  'PJCAD' and coalesce(e.sigla,'---') <> 'CI' and acesso(d.sq_siw_solicitacao, p_usuario) >= 8)
                                 )
                             and 'CI'    <> coalesce(e.sigla,'nulo')
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          x on (v.sq_menu = x.sq_menu)
              inner join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) as qtd 
                            FROM siw_tramite                      e
                                 inner     join siw_menu          c on (e.sq_menu        = c.sq_menu) 
                                   inner   join siw_modulo        b on (c.sq_modulo      = b.sq_modulo)
                           where c.tramite       = 'S' 
                             and (e.ativo = 'S' or (e.sigla = 'AT' and d.solicitante = p_usuario and c.consulta_opiniao = 'S' and d.opiniao is null))
                             and c.sq_pessoa     = p_cliente
                             and acesso(d.sq_siw_solicitacao, p_usuario) > 0
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          y on (v.sq_menu = y.sq_menu)
           where v.tramite   = 'S' 
             and v.ativo     = 'S' 
             and v.sq_pessoa = p_cliente
          order by or_modulo, nm_modulo, nm_servico;
   Else
      -- Recupera a lista de solicita��es da mesa de trabalho do usu�rio
      open p_result for
         select v.sq_menu, v.sq_pessoa, w.sq_modulo, w.ordem as or_modulo, w.nome as nm_modulo, w.sigla as sg_modulo, v.sq_menu, v.nome as nm_servico, 
                v.link, v.imagem, v.p1, v.p2, v.p3, v.p4, v.sigla as sg_servico, x.qtd
         from siw_menu              v
              inner join siw_modulo w on (v.sq_modulo = w.sq_modulo)
              inner join (select /*+ ordered */ c.sq_menu, count(d.sq_siw_solicitacao) as qtd 
                            FROM siw_tramite                    e
                                 inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                                   inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                                      c.tramite        = 'S' and
                                                                      c.ativo          = 'S' and
                                                                      c.sq_pessoa      = p_cliente
                                                                     ) 
                                     inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
                           where e.ativo  = 'S'
                             and acesso(d.sq_siw_solicitacao, p_usuario) > 0
                             and 'CI'     <> coalesce(e.sigla,'nulo')
                             and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
                           group by c.sq_menu
                         )          x on (v.sq_menu = x.sq_menu)
           where v.tramite   = 'S' 
             and v.ativo     = 'S' 
             and v.sq_pessoa = p_cliente
          order by or_modulo, nm_modulo, nm_servico;
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
