create or replace procedure SP_GetTramiteUser
   (p_cliente     in  number    default null,
    p_sq_menu     in  number    default null,
    p_ChaveAux    in  number    default null,
    p_retorno     in  varchar2,
    p_nome        in  varchar2  default null,
    p_sq_unidade  in  number    default null,
    p_acesso      in  number    default null,
    p_result      out sys_refcursor
   ) is
begin
   If p_retorno = 'USUARIO' Then
      -- Recupera os usuários habilitados para uma opção do menu
      open p_result for
         select a.descentralizado, d.logradouro, e.username, c.nome, c.sq_pessoa, d.sq_pessoa_endereco 
         from siw_menu            a, 
              sg_tramite_pessoa   b, 
              co_pessoa           c, 
              co_pessoa_endereco  d, 
              sg_autenticacao     e,
              siw_tramite         f
         where a.sq_menu             = f.sq_menu 
           and b.sq_siw_tramite      = f.sq_siw_tramite
           and b.sq_pessoa           = c.sq_pessoa 
           and b.sq_pessoa_endereco  = d.sq_pessoa_endereco 
           and b.sq_pessoa           = e.sq_pessoa 
           and f.sq_siw_tramite      = p_ChaveAux
         order by d.logradouro, c.nome_indice;
   Elsif p_retorno = 'VINCULO' Then
      -- Recupera os tipos de vínculo habilitados para um trâmite
      null;
   Elsif p_retorno = 'PESQUISA' Then
      -- Recupera os usuarios habilitados para uma opção do menu a partir de outra opção
      open p_result for
         select b.sq_pessoa, b1.nome, b1.nome_indice, a.sq_unidade, 
                marcado(Nvl(p_ChaveAux,-1), b.sq_pessoa) acesso
          from eo_localizacao  a, 
               sg_autenticacao b, 
               co_pessoa       b1,
               siw_menu        c 
         where b.sq_pessoa      = b1.sq_pessoa 
           and a.sq_localizacao = b.sq_localizacao 
           and b.ativo          = 'S' 
           and b1.sq_pessoa_pai = p_cliente
           and c.sq_menu        = p_sq_menu
           and marcado(c.sq_menu, b.sq_pessoa,null,p_ChaveAux) = 0
           and ((p_nome       is null) or (p_nome       is not null and b1.nome_indice like '%'||acentos(p_nome)||'%'))
           and ((p_sq_unidade is null) or (p_sq_unidade is not null and a.sq_unidade   = p_sq_unidade))
           and ((p_acesso     is null) or (p_acesso     is not null and(marcado(p_acesso, b.sq_pessoa, p_ChaveAux)) > 0))
         ORDER BY 3;
   Elsif p_retorno = 'ACESSO' Then
      -- Recupera os tramites habilitados para um usuário
      open p_result for
          select a.sq_modulo, a.nome nm_modulo,
                 b.sq_menu,   b.nome nm_servico,
                 c.sq_siw_tramite, c.nome nm_tramite,
                 d.sq_pessoa, d.sq_pessoa_endereco,
                 e.qtd_servico,
                 f.qtd_tramite
            from siw_modulo                 a
                 inner     join siw_menu    b on (a.sq_modulo            = b.sq_modulo)
                   inner   join siw_tramite c on (b.sq_menu              = c.sq_menu)
                     inner join sg_tramite_pessoa d on (c.sq_siw_tramite = d.sq_siw_tramite)
                     inner join (select count(x.sq_menu) qtd_servico, x.sq_modulo
                                   from siw_menu                       x
                                        inner   join siw_tramite       y on (x.sq_menu        = y.sq_menu)
                                          inner join sg_tramite_pessoa z on (y.sq_siw_tramite = z.sq_siw_tramite and
                                                                             p_ChaveAux       = z.sq_pessoa)
                                  where x.ativo = 'S'
                                  group by x.sq_modulo
                                )           e on (a.sq_modulo            = e.sq_modulo)
                     inner join (select count(y.sq_siw_tramite) qtd_tramite, y.sq_menu
                                   from siw_tramite                  y
                                        inner join sg_tramite_pessoa z on (y.sq_siw_tramite = z.sq_siw_tramite and
                                                                             p_ChaveAux       = z.sq_pessoa)
                                  where y.ativo = 'S'
                                  group by y.sq_menu
                                )           f on (b.sq_menu              = f.sq_menu)
 where d.sq_pessoa = p_ChaveAux;
   End If;  
end SP_GetTramiteUser;
/
