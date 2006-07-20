create or replace procedure SP_GetUorgList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_nome      in varchar2 default null,
    p_sigla     in varchar2 default null,
    p_ano       in number   default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'LICITACAO' or p_restricao = 'ATIVO' or p_restricao = 'CODIGO' or
      p_restricao = 'CODIGONULL' Then
      -- Recupera as unidades organizacionais do cliente
      open p_result for
         select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                b.sq_pessoa_endereco, b.logradouro,
                e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao,
                e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                f.nome nm_cidade, f.co_uf
           from eo_unidade         a,
                co_pessoa_endereco b,
                co_cidade          f,
                eo_unidade_resp    c,
                co_pessoa          d,
                lc_unidade         e
          where (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
            and (b.sq_cidade          = f.sq_cidade)
            and (a.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)   = 'T' and 
                 c.fim (+)            is null)
            and (c.sq_pessoa          = d.sq_pessoa (+))
            and (a.sq_unidade         = e.sq_unidade (+))
            and b.sq_pessoa           = p_cliente
            and (p_chave              is null or (p_chave is not null and a.sq_unidade = p_chave))            
            and (p_nome               is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
            and (p_sigla              is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
            and (p_restricao          is null or (p_restricao = 'LICITACAO'  and (a.ativo    = 'S' and e.ativo = 'S' and e.contrata = 'S'))
                                              or (p_restricao = 'ATIVO'      and (a.ativo    = 'S'))
                                              or (p_restricao = 'CODIGO'     and (a.informal = 'N' and a.sq_unidade_pai is null))
                                              or (p_restricao = 'CODIGONULL' and (a.informal = 'N' and a.codigo <> '00')))                                                         
         order by a.nome;
   Else
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, Nvl(d.nome,'Informar') responsavel
              from eo_unidade         a,
                   eo_unidade_resp    c,
                   co_pessoa          d,
                   co_pessoa_endereco b
             where (a.sq_unidade       = c.sq_unidade (+) and 
                    c.tipo_respons (+) = 'T'          and 
                    c.fim (+)          is null)
               and (c.sq_pessoa = d.sq_pessoa (+))
               and a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       is null
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla               is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
            order by a.nome;
      Elsif p_restricao = 'VIAGEM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   decode(a.ativo,'S','Sim','Não') nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf
              from pd_unidade         a,
                   eo_unidade         b,
                   co_pessoa_endereco c,
                   co_cidade          f
             where (a.sq_unidade         = b.sq_unidade)
               and (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
               and (c.sq_cidade          = f.sq_cidade)
               and c.sq_pessoa           = p_cliente
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
      Elsif p_restricao = 'PDUNIDLIM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   decode(a.ativo,'S','Sim','Não') nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf,
                   g.ano, nvl(g.limite_passagem,0) limite_passagem, nvl(g.limite_diaria,0) limite_diaria
              from pd_unidade         a,
                   eo_unidade         b,
                   co_pessoa_endereco c,
                   co_cidade          f,
                   pd_unidade_limite  g
             where (a.sq_unidade = b.sq_unidade)
               and (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
               and (c.sq_cidade          = f.sq_cidade)
               and (a.sq_unidade         = g.sq_unidade and
                    ((p_ano              is null) or (p_ano is not null and g.ano = p_ano))
                   )
               and c.sq_pessoa   = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
      Elsif p_restricao = 'VIAGEMUNID' Then
         open p_result for 
            select x.sq_unidade, y.nivel
              from pd_unidade x,
                   (select sq_unidade, nome, level nivel
                      from eo_unidade a
                    start with a.sq_unidade = p_chave
                    connect by prior a.sq_unidade_pai = a.sq_unidade
                   ) y
            where x.sq_unidade = y.sq_unidade
            order by y.nivel;
      ElsIf p_restricao = 'LICITACAOEND' Then
         open p_result for          
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao,
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade         a,
                   co_pessoa_endereco b,
                   co_cidade          f,
                   eo_unidade_resp    c,
                   co_pessoa          d,
                   lc_unidade         e
             where (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
               and (b.sq_cidade          = f.sq_cidade)
               and (a.sq_unidade         = c.sq_unidade (+) and
                    c.tipo_respons (+)   = 'T' and 
                    c.fim (+)            is null)
               and (c.sq_pessoa          = d.sq_pessoa (+))
               and (a.sq_unidade         = e.sq_unidade (+))
               and b.sq_pessoa           = p_cliente
               and (p_nome               is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla              is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
               and a.ativo                = 'S' 
               and e.ativo                = 'S' 
               and e.contrata             = 'S' 
               and b.sq_pessoa_endereco = p_chave;
      Elsif p_restricao = 'GESTORA' Then
         open p_result for          
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao,
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade         a,
                   co_pessoa_endereco b,
                   co_cidade          f,
                   eo_unidade_resp    c,
                   co_pessoa          d,
                   lc_unidade         e
             where (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
               and (b.sq_cidade          = f.sq_cidade)
               and (a.sq_unidade         = c.sq_unidade (+) and
                    c.tipo_respons (+)   = 'T' and 
                    c.fim (+)            is null)
               and (c.sq_pessoa          = d.sq_pessoa (+))
               and (a.sq_unidade         = e.sq_unidade (+))
               and b.sq_pessoa           = p_cliente
               and (p_nome               is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla              is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
               and a.unidade_gestora      = 'S' 
               and a.ativo                = 'S' 
               and (p_chave is null or (p_chave is not null and a.sq_unidade <> p_chave));
      Elsif p_restricao = 'PAGADORA' Then
         open p_result for          
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao,
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade         a,
                   co_pessoa_endereco b,
                   co_cidade          f,
                   eo_unidade_resp    c,
                   co_pessoa          d,
                   lc_unidade         e
             where (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
               and (b.sq_cidade          = f.sq_cidade)
               and (a.sq_unidade         = c.sq_unidade (+) and
                    c.tipo_respons (+)   = 'T' and 
                    c.fim (+)            is null)
               and (c.sq_pessoa          = d.sq_pessoa (+))
               and (a.sq_unidade         = e.sq_unidade (+))
               and b.sq_pessoa           = p_cliente
               and (p_nome               is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla              is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
               and a.unidade_pagadora    = 'S' 
               and a.ativo               = 'S' 
               and (p_chave is null or (p_chave is not null and a.sq_unidade <> p_chave));
      Elsif p_restricao = 'VALORCODIGO' Then
         open p_result for          
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao,
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade         a,
                   co_pessoa_endereco b,
                   co_cidade          f,
                   eo_unidade_resp    c,
                   co_pessoa          d,
                   lc_unidade         e
             where (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
               and (b.sq_cidade          = f.sq_cidade)
               and (a.sq_unidade         = c.sq_unidade (+) and
                    c.tipo_respons (+)   = 'T' and 
                    c.fim (+)            is null)
               and (c.sq_pessoa          = d.sq_pessoa (+))
               and (a.sq_unidade         = e.sq_unidade (+))
               and b.sq_pessoa           = p_cliente
               and (p_nome               is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla              is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
               and a.informal             = 'N' 
               and a.codigo               = p_chave;
      Else
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada,
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, Nvl(d.nome,'Informar') responsavel
              from eo_unidade          a,
                   eo_unidade_resp     c, 
                   co_pessoa           d,
                   co_pessoa_endereco  b
             where (a.sq_unidade   = c.sq_unidade (+) and 
                    c.tipo_respons (+) = 'T'          and 
                    c.fim (+)          is null)
               and (c.sq_pessoa = d.sq_pessoa (+))
               and a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       = p_chave
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla               is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
            order by a.nome;
      End If;
   End If;
end SP_GetUorgList;
/
