create or replace procedure SP_GetViagemBenef
   (p_chave       in number  default null,
    p_cliente     in number,
    p_sq_pessoa   in number   default null,
    p_restricao   in varchar2 default null,
    p_cpf         in varchar2 default null,
    p_nome        in varchar2 default null,
    p_dt_ini      in date     default null,
    p_dt_fim      in date     default null,
    p_chave_aux   in number   default null,
    p_result      out sys_refcursor
   ) is
   
begin
   If p_restricao is null Then
      open p_result for 
        select a.sq_pessoa, a.nome nm_pessoa, a.nome_resumido, a.sq_pessoa_pai, 
               a.cliente, a.fornecedor,
               c.sq_tipo_pessoa, c.nome nm_tipo_pessoa,
               d.sq_tipo_vinculo, d.nome nm_tipo_vinculo, d.interno, d.ativo vinculo_ativo,
               b.sq_pessoa_fax, b.nr_fax,
               f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
               l.sq_pessoa_celular, l.nr_celular,
               i.email,
               j.cpf, j.rg_numero, j.rg_emissao, j.rg_emissor, j.sexo,
               j.passaporte_numero, j.sq_pais_passaporte,
               case j.sexo when 'F' then 'Feminino' else 'Masculino' end nm_sexo,
               e.origem, e.destino, e.reserva, e.bilhete, e.trechos, e.sq_viagem,
               e.saida, e.retorno, e.valor,
               o.nome nm_pais_passaporte, p.sq_pais sq_pais_origem, p.co_uf co_uf_origem,
               q.co_uf co_uf_destino, q.sq_pais sq_pais_destino,
               case upper(p1.nome) when 'BRASIL' then p.nome||'-'||p.co_uf||' ('||p1.nome||')' else p.nome||' ('||p1.nome||')' end nm_cidade_origem,
               case upper(q1.nome) when 'BRASIL' then q.nome||'-'||q.co_uf||' ('||q1.nome||')' else q.nome||' ('||q1.nome||')' end nm_cidade_destino
          from co_pessoa                                 a
               left outer   join  pd_viagem              e  on (a.sq_pessoa          = e.pessoa)
                 left outer join  co_cidade              p  on (e.origem             = p.sq_cidade)
                  left outer join co_pais                p1 on (p.sq_pais            = p1.sq_pais)
                 left outer join  co_cidade              q  on (e.destino            = q.sq_cidade)
                  left outer join co_pais                q1 on (q.sq_pais            = q1.sq_pais)
                 left outer join  siw_solicitacao        g  on (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
               inner      join  co_tipo_pessoa           c  on (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
               inner      join  co_tipo_vinculo          d  on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
               left outer join  (select sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Fax'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        b  on (a.sq_pessoa          = b.sq_pessoa)
               left outer join  (select sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Comercial'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        f  on (a.sq_pessoa          = f.sq_pessoa)
               left outer join  (select sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                                   from co_pessoa_telefone          w
                                        inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                        inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.nome               = 'Celular'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        l  on (a.sq_pessoa          = l.sq_pessoa)
               left outer join  (select sq_pessoa, logradouro email
                                   from co_pessoa_endereco            w
                                        inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                        inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                          inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                  where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                    and x.email              = 'S'
                                    and x.ativo              = 'S'
                                    and w.padrao             = 'S'
                                )                        i  on (a.sq_pessoa      = i.sq_pessoa)
               inner      join co_pessoa_fisica          j  on (a.sq_pessoa      = j.sq_pessoa)
                 left outer join co_pais                 o on (o.sq_pais        = j.sq_pais_passaporte)
         where (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_nome         is null or (p_nome        is not null and (a.nome_indice   like(upper(acentos('%'||p_nome||'%')))) or (a.nome_resumido_ind like(upper(acentos('%'||p_nome||'%')))) ))
           and (p_cpf          is null or (p_cpf         is not null and (j.cpf           = p_cpf)))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave))
           and (p_dt_ini       is null or (p_dt_ini      is not null and (e.saida between p_dt_ini and p_dt_fim) or (e.retorno between p_dt_ini and p_dt_fim)));
   End If;
end SP_GetViagemBenef;
/
