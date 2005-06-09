create or replace procedure SP_GetUorgList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_nome      in varchar2 default null,
    p_sigla     in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as unidades organizacionais do cliente
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

