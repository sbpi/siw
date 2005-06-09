create or replace procedure SP_GetAcordoRep
   (p_chave       in number,
    p_cliente     in number,
    p_sq_pessoa   in number   default null,
    p_restricao   in varchar2 default null,
    p_result      out siw.sys_refcursor
   ) is

   w_sg_modulo       varchar2(10);
begin
   -- Recupera o módulo ao qual a solicitação pertence
   select c.sigla into w_sg_modulo
     from siw_solicitacao         a,
          siw_menu   b,
            siw_modulo c
    where (a.sq_menu   = b.sq_menu)
      and (b.sq_modulo = c.sq_modulo)
      and sq_siw_solicitacao = p_chave;

   If w_sg_modulo = 'AC' Then
      open p_result for
        select a.sq_pessoa, a.nome nm_pessoa, a.nome_resumido, a.sq_pessoa_pai,
               a.cliente, a.fornecedor,
               c.sq_tipo_pessoa, c.nome nm_tipo_pessoa,
               d.sq_tipo_vinculo, d.nome nm_tipo_vinculo, d.interno, d.ativo vinculo_ativo,
               b.sq_pessoa_fax, b.nr_fax,
               f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
               l.sq_pessoa_celular, l.nr_celular,
               i.email,
               Nvl(j.cpf,n.username) cpf, j.rg_numero, j.rg_emissao, j.rg_emissor, j.sexo
          from co_pessoa                                a,
               ac_acordo_representante e,
                 ac_acordo               g,
                   co_pessoa               h,
               co_tipo_pessoa          c,
               co_tipo_vinculo         d,
               (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Fax'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       b,
               (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Comercial'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       f,
               (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Celular'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       l,
               (select w.sq_pessoa, logradouro email
                                   from co_pessoa_endereco            w,
                                        co_tipo_endereco x,
                                        co_pessoa        y,
                                          co_tipo_pessoa   z
                                  where (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                    and (w.sq_pessoa          = y.sq_pessoa)
                                    and (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.email              = 'S'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       i,
               co_pessoa_fisica         j,
               sg_autenticacao          n
         where (a.sq_pessoa          = e.sq_pessoa)
           and (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
           and (g.outra_parte        = h.sq_pessoa)
           and (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
           and (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
           and (a.sq_pessoa      = b.sq_pessoa (+))
           and (a.sq_pessoa      = f.sq_pessoa (+))
           and (a.sq_pessoa      = l.sq_pessoa (+))
           and (a.sq_pessoa      = i.sq_pessoa (+))
           and (a.sq_pessoa      = j.sq_pessoa)
           and (a.sq_pessoa      = n.sq_pessoa)
           and (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave));
   Elsif w_sg_modulo = 'PR' Then
      open p_result for
        select a.sq_pessoa, a.nome nm_pessoa, a.nome_resumido, a.sq_pessoa_pai,
               a.cliente, a.fornecedor,
               c.sq_tipo_pessoa, c.nome nm_tipo_pessoa,
               d.sq_tipo_vinculo, d.nome nm_tipo_vinculo, d.interno, d.ativo vinculo_ativo,
               b.sq_pessoa_fax, b.nr_fax,
               f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
               l.sq_pessoa_celular, l.nr_celular,
               i.email,
               Nvl(j.cpf,n.username) cpf, j.rg_numero, j.rg_emissao, j.rg_emissor, j.sexo
          from co_pessoa                                 a,
               pj_projeto_representante e,
                 pj_projeto               g,
                   co_pessoa                h,
               co_tipo_pessoa           c,
               co_tipo_vinculo          d,
               (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Fax'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        b,
               (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where(w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Comercial'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        f,
               (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                                   from co_pessoa_telefone          w,
                                        co_tipo_telefone x,
                                        co_pessoa        z
                                  where(w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                    and (w.sq_pessoa          = z.sq_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Celular'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        l,
               (select w.sq_pessoa, logradouro email
                                   from co_pessoa_endereco            w,
                                        co_tipo_endereco x,
                                        co_pessoa        y,
                                          co_tipo_pessoa   z
                                  where (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                    and (w.sq_pessoa          = y.sq_pessoa)
                                    and (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                    and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.email              = 'S'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        i,
               co_pessoa_fisica          j,
               sg_autenticacao           n
         where (a.sq_pessoa          = e.sq_pessoa)
           and (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
           and (g.outra_parte        = h.sq_pessoa)
           and (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
           and (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
           and (a.sq_pessoa      = b.sq_pessoa (+))
           and (a.sq_pessoa      = f.sq_pessoa (+))
           and (a.sq_pessoa      = l.sq_pessoa(+))
           and (a.sq_pessoa      = i.sq_pessoa (+))
           and (a.sq_pessoa      = j.sq_pessoa)
           and (a.sq_pessoa      = n.sq_pessoa)
           and (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave));
   End If;
end SP_GetAcordoRep;
/

