create or replace procedure SP_GetAcordoRep
   (p_chave       in number,
    p_cliente     in number,
    p_sq_pessoa   in number   default null,
    p_restricao   in varchar2 default null,
    p_result      out sys_refcursor
   ) is

   w_sg_modulo       varchar2(10);
begin
   -- Recupera o módulo ao qual a solicitação pertence
   select c.sigla into w_sg_modulo
     from siw_solicitacao         a
          inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
            inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
    where sq_siw_solicitacao = p_chave;

   If w_sg_modulo = 'AC' Then
      open p_result for 
        select a.sq_pessoa, a.nome as nm_pessoa, a.nome_resumido, a.sq_pessoa_pai, 
               a.cliente, a.fornecedor,
               c.sq_tipo_pessoa, c.nome as nm_tipo_pessoa,
               d.sq_tipo_vinculo, d.nome as nm_tipo_vinculo, d.interno, d.ativo as vinculo_ativo,
               b.sq_pessoa_fax, b.nr_fax,
               f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
               l.sq_pessoa_celular, l.nr_celular,
               i.email,
               Nvl(j.cpf,n.username) as cpf, j.rg_numero, j.rg_emissao, j.rg_emissor, j.sexo
          from co_pessoa                                a
               inner      join  ac_acordo_representante e on (a.sq_pessoa          = e.sq_pessoa)
                 inner    join  ac_acordo               g on (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
                   inner  join  co_pessoa               h on (g.outra_parte        = h.sq_pessoa)
               inner      join  co_tipo_pessoa          c on (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
               inner      join  co_tipo_vinculo         d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone as sq_pessoa_fax, w.numero as nr_fax
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Fax'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       b on (a.sq_pessoa          = b.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero as nr_telefone
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Comercial'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       f on (a.sq_pessoa          = f.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone as sq_pessoa_celular, w.numero as nr_celular
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Celular'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       l on (a.sq_pessoa          = l.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.logradouro as email
                                   from co_pessoa_endereco            w
                                        inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                        inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                          inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.email              = 'S'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                       i on (a.sq_pessoa      = i.sq_pessoa)
               inner      join co_pessoa_fisica         j on (a.sq_pessoa      = j.sq_pessoa)
               inner      join sg_autenticacao          n on (a.sq_pessoa      = n.sq_pessoa)
         where (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave));
   Elsif w_sg_modulo = 'PR' Then
      open p_result for 
        select a.sq_pessoa, a.nome as nm_pessoa, a.nome_resumido, a.sq_pessoa_pai, 
               a.cliente, a.fornecedor,
               c.sq_tipo_pessoa, c.nome as nm_tipo_pessoa,
               d.sq_tipo_vinculo, d.nome as nm_tipo_vinculo, d.interno, d.ativo vinculo_ativo,
               b.sq_pessoa_fax, b.nr_fax,
               f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
               l.sq_pessoa_celular, l.nr_celular,
               i.email,
               Nvl(j.cpf,n.username) as cpf, j.rg_numero, j.rg_emissao, j.rg_emissor, j.sexo
          from co_pessoa                                 a
               inner      join  pj_projeto_representante e on (a.sq_pessoa          = e.sq_pessoa)
                 inner    join  pj_projeto               g on (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
                   inner  join  co_pessoa                h on (g.outra_parte        = h.sq_pessoa)
               inner      join  co_tipo_pessoa           c on (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
               inner      join  co_tipo_vinculo          d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone as sq_pessoa_fax, w.numero as nr_fax
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Fax'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        b on (a.sq_pessoa          = b.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero as nr_telefone
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Comercial'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        f on (a.sq_pessoa          = f.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.sq_pessoa_telefone as sq_pessoa_celular, w.numero as nr_celular
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Celular'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        l on (a.sq_pessoa          = l.sq_pessoa)
               left outer join  (select w.sq_pessoa, w.logradouro as email
                                   from co_pessoa_endereco            w
                                        inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                        inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                          inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.email              = 'S'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        i on (a.sq_pessoa      = i.sq_pessoa)
               inner      join co_pessoa_fisica          j on (a.sq_pessoa      = j.sq_pessoa)
               inner      join sg_autenticacao           n on (a.sq_pessoa      = n.sq_pessoa)
         where (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave));
   End If;
end SP_GetAcordoRep;
/
