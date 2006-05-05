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
         select /*+ ordered */ c.sq_pessoa, 
                b.sq_modulo, b.nome nm_modulo, b.sigla sg_modulo, b.objetivo_geral, 
                c.sq_menu, c.nome nm_servico, c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla sg_servico, 
                count(d.sq_siw_solicitacao) qtd 
           FROM siw_tramite                    e
                inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                  inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                     c.tramite        = 'S' and
                                                     c.ativo          = 'S' and
                                                     c.sq_pessoa      = p_cliente
                                                    ) 
                    inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
          where e.ativo = 'S' 
            and d.executor = p_usuario
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
           FROM siw_tramite                    e
                inner     join siw_solicitacao d on (e.sq_siw_tramite = d.sq_siw_tramite)
                  inner   join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                  from siw_solicitacao
                               )               f on (d.sq_siw_solicitacao = f.sq_siw_solicitacao and
                                                     f.acesso             > 0
                                                    )
                  inner   join siw_menu        c on (d.sq_menu        = c.sq_menu and
                                                     c.tramite        = 'S' and
                                                     c.ativo          = 'S' and
                                                     c.sq_pessoa      = p_cliente
                                                    ) 
                    inner join siw_modulo      b on (c.sq_modulo      = b.sq_modulo)
          where e.ativo = 'S' 
            and 'CI'    <> Nvl(e.sigla,'nulo')
            and (c.controla_ano = 'N' or (c.controla_ano = 'S' and d.ano = p_ano))
          group by c.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral, c.sq_menu, c.nome, 
                   c.link, c.imagem, c.p1, c.p2, c.p3, c.p4, c.sigla 
          order by b.nome, c.nome;
   End If;
end SP_GetDeskTop;
/
