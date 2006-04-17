create or replace function SP_GetUorgList
   (p_cliente   numeric,
    p_chave     numeric,
    p_restricao varchar,
    p_nome      varchar,
    p_sigla     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   If p_restricao is null Then
      -- Recupera as unidades organizacionais do cliente
      open p_result for 
         select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                b.sq_pessoa_endereco, b.logradouro,
                e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                f.nome as nm_cidade, f.co_uf
           from eo_unidade                         a
                inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                  inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                         c.tipo_respons       = 'T' and c.fim is null)
                left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
          where b.sq_pessoa            = p_cliente
            and (p_nome                is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
            and (p_sigla               is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
         order by a.nome;
   Else
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, coalesce(d.nome,'Informar') as responsavel
              from eo_unidade             a  left outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                                               and c.tipo_respons = 'T'
                                                                               and c.fim is null)
                                             left outer join co_pessoa d on (c.sq_pessoa = d.sq_pessoa),                                   
                   co_pessoa_endereco     b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       is null
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla               is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
            order by nome;
      Elsif p_restricao = 'VIAGEM' Then
         open p_result for 
            select a.sq_unidade as chave, a.limite_passagem, a.limite_diaria, a.ativo, a.ano, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   b.nome, b.sigla
              from pd_unidade                           a
                     left outer join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                     left outer join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
             where c.sq_pessoa = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));      
      Else
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, coalesce(d.nome,'Informar') as responsavel
              from eo_unidade             a left outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                                              and c.tipo_respons = 'T'
                                                                              and c.fim is null)
                                            left outer join co_pessoa d on (c.sq_pessoa = d.sq_pessoa),                                   
                   co_pessoa_endereco     b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       = p_chave
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and a.nome  like '%'||p_nome||'%'))
               and (p_sigla               is null or (p_sigla is not null and a.sigla like '%'||p_sigla||'%'))
            order by nome;
      End If;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;