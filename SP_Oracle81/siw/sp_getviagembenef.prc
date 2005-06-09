create or replace procedure SP_GetViagemBenef
   (p_chave       in number  default null,
    p_cliente     in number,
    p_sq_pessoa   in number   default null,
    p_restricao   in varchar2 default null,
    p_cpf         in varchar2 default null,
    p_nome        in varchar2 default null,
    p_result      out siw.sys_refcursor
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
               decode(j.sexo,'F','Feminino','Masculino') nm_sexo,
               e.origem, e.destino, e.reserva, e.bilhete, e.trechos, e.sq_viagem,
               e.saida, e.retorno, e.valor,
               o.nome nm_pais_passaporte, p.sq_pais sq_pais_origem, p.co_uf co_uf_origem,
               q.co_uf co_uf_destino, q.sq_pais sq_pais_destino,
               decode(upper(p1.nome),'BRASIL', p.nome||'-'||p.co_uf||' ('||p1.nome||')',p.nome||' ('||p1.nome||')') nm_cidade_origem,
               decode(upper(q1.nome),'BRASIL',q.nome||'-'||q.co_uf||' ('||q1.nome||')',q.nome||' ('||q1.nome||')') nm_cidade_destino
          from co_pessoa                                 a,
               pd_viagem              e,
               co_cidade              p,
               co_pais                p1,
               co_cidade              q,
               co_pais                q1,
               siw_solicitacao        g,
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
               (select  w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                  from co_pessoa_telefone          w,
                       co_tipo_telefone x,
                       co_pessoa        z
                 where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                   and (w.sq_pessoa          = z.sq_pessoa)
                   and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                   and x.nome               = 'Comercial'
                   and x.ativo              = 'S'
                   and w.padrao             = 'S'
               )                        f,
               (select  w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                  from co_pessoa_telefone          w,
                       co_tipo_telefone x,
                       co_pessoa        z
                 where (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                   and (w.sq_pessoa          = z.sq_pessoa)
                   and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                   and x.nome               = 'Celular'
                   and x.ativo              = 'S'
                   and w.padrao             = 'S'
               )                        l,
               (select  w.sq_pessoa, logradouro email
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
               co_pais                 o 
         where  (a.sq_pessoa          = e.pessoa (+))
           and (e.origem             = p.sq_cidade (+))
           and (p.sq_pais            = p1.sq_pais (+))
           and (e.destino            = q.sq_cidade (+))
           and (q.sq_pais            = q1.sq_pais (+))
           and (e.sq_siw_solicitacao = g.sq_siw_solicitacao (+))
           and (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
           and (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
           and (a.sq_pessoa          = b.sq_pessoa (+))
           and (a.sq_pessoa          = f.sq_pessoa (+))
           and (a.sq_pessoa          = l.sq_pessoa (+))
           and (a.sq_pessoa      = i.sq_pessoa (+))
           and (a.sq_pessoa      = j.sq_pessoa)
           and (o.sq_pais        = j.sq_pais_passaporte (+))
           and (a.sq_pessoa_pai = p_cliente or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
           and (p_nome         is null or (p_nome        is not null and (a.nome_indice   like(upper(acentos('%'||p_nome||'%')))) or (a.nome_resumido_ind like(upper(acentos('%'||p_nome||'%')))) ))
           and (p_cpf          is null or (p_cpf         is not null and (j.cpf           = p_cpf)))
           and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa      = p_sq_pessoa))
           and (p_chave        is null or (p_chave       is not null and e.sq_siw_solicitacao = p_chave));
   End If;
end SP_GetViagemBenef;
/

