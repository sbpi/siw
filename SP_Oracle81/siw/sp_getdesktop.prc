create or replace procedure SP_GetDeskTop
   (p_cliente   in  number,
    p_usuario   in number,
    p_ano       in number,
    p_result    out siw.sys_refcursor
   ) is
   w_interno  varchar2(1);
begin
   -- Verifica se o vínculo do usuário com a organização é interno ou externo
   select decode(count(*),0,'N','S') into w_interno
     from co_pessoa       a,
          co_tipo_vinculo b
         where a.sq_tipo_vinculo = b.sq_tipo_vinculo
           and b.interno         = 'S'
           and a.sq_pessoa       = p_usuario;

   If w_interno = 'S' Then
      -- Recupera a lista de solicitações da mesa de trabalho do usuário
      open p_result for
         select /*+ ordered */ c.sq_pessoa,
                b.sq_modulo, b.nome nm_modulo, b.sigla sg_modulo, b.objetivo_geral,
                c.sq_menu, c.nome nm_servico, c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla sg_servico,
                count(d.sq_siw_solicitacao) qtd
           FROM siw_tramite                    e,
                siw_solicitacao d,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                   from siw_solicitacao
                )               f,
                siw_menu        c,
                siw_modulo      b
          where (e.sq_siw_tramite = d.sq_siw_tramite)
            and (d.sq_menu        = c.sq_menu and
                 c.tramite        = 'S' and
                 c.ativo          = 'S' and
                 c.sq_pessoa      = p_cliente
                )
            and (c.sq_modulo      = b.sq_modulo)
            and (e.ativo = 'S' or (e.sigla = 'AT' and d.solicitante = p_usuario and c.consulta_opiniao = 'S' and d.opiniao is null))
            and (d.sq_siw_solicitacao = f.sq_siw_solicitacao)
            and ((c.destinatario = 'S' and d.executor = p_usuario) or (c.destinatario = 'N' and f.acesso > 15))
            and 'CI'    <> Nvl(e.sigla,'nulo')
            and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
          group by c.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral, c.sq_menu, c.nome,
                   c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla
          order by b.nome, c.nome;
   Else
      -- Recupera a lista de solicitações da mesa de trabalho do usuário
      open p_result for
         select /*+ ordered */ c.sq_pessoa,
                b.sq_modulo, b.nome nm_modulo, b.sigla sg_modulo, b.objetivo_geral,
                c.sq_menu, c.nome nm_servico, c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla sg_servico,
                count(d.sq_siw_solicitacao) qtd
           FROM siw_tramite                    e,
                siw_solicitacao d,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                 from siw_solicitacao
                )               f,
                siw_menu        c,
                siw_modulo      b
          where (e.sq_siw_tramite = d.sq_siw_tramite)
            and (d.sq_siw_solicitacao = f.sq_siw_solicitacao and
                 f.acesso             > 0
                )
            and (d.sq_menu        = c.sq_menu and
                 c.tramite        = 'S' and
                 c.ativo          = 'S' and
                 c.sq_pessoa      = p_cliente
                )
            and (c.sq_modulo      = b.sq_modulo)
            and e.ativo = 'S'
            and 'CI'    <> Nvl(e.sigla,'nulo')
            and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
          group by c.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral, c.sq_menu, c.nome,
                   c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla
          order by b.nome, c.nome;
   End If;
end SP_GetDeskTop;
/
