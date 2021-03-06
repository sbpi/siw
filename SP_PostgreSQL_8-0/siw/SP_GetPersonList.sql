create or replace function SP_GetPersonList
   (p_cliente    numeric,
    p_chave      numeric,
    p_restricao  varchar,
    p_nome       varchar,
    p_sg_unidade varchar,
    p_codigo     varchar,
    p_filhos     varchar,
    p_result    refcursor
   ) returns refcursor as $$
declare
  l_item       varchar(18);
  l_tipo       varchar(200) := p_restricao ||',';
  x_tipo       varchar(200) := '';
begin
   If p_restricao = 'PESSOA' or p_restricao = 'NOVOUSO' Then
      -- Recupera as pessoas da organiza��o
      open p_result for 
         select a.sq_pessoa, coalesce(b.cpf, c.username) as cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                c.username,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 left outer    join co_pessoa_fisica  b on (a.sq_pessoa      = b.sq_pessoa)
                 left outer    join sg_autenticacao   c on (a.sq_pessoa      = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade     = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao = e.sq_localizacao)
          where a.sq_pessoa_pai = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))
            and (p_restricao  is null or (p_restricao = 'NOVOUSO' and c.username is null))
         order by a.nome_indice;
   Elsif p_restricao = 'TODOS' Then
      -- Recupera todas as pessoas do cadastro da organiza��o, f�sicas e jur�dicas
      open p_result for 
         select a.sq_pessoa, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                b.codigo,
                c.username, c.ativo as usuario,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                              a
                left outer join (select x.sq_pessoa, 
                                        case when y.sq_pessoa is not null 
                                             then y.cpf
                                             else case when z.sq_pessoa is not null
                                                       then z.cnpj
                                                       else null
                                                  end
                                        end as codigo
                                   from co_pessoa                          x
                                        left outer join co_pessoa_fisica   y on (x.sq_pessoa = y.sq_pessoa)
                                        left outer join co_pessoa_juridica z on (x.sq_pessoa = z.sq_pessoa)
                                )                      b on (a.sq_pessoa      = b.sq_pessoa)
                 left outer    join sg_autenticacao    c on (a.sq_pessoa      = c.sq_pessoa) 
                    left outer join eo_unidade         d on (c.sq_unidade     = d.sq_unidade)
                    left outer join eo_localizacao     e on (c.sq_localizacao = e.sq_localizacao)
          where (a.sq_pessoa = p_cliente or a.sq_pessoa_pai = p_cliente)
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))
            and (p_codigo     is null or (p_codigo     is not null and b.codigo = p_codigo))
         order by a.nome_indice;
   Elsif p_restricao = 'INTERNOS' Then
      -- Recupera as pessoas internas � organiza��o
      open p_result for 
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla as sg_unidade, f.nome as nm_unidade, g.nome as nm_local
          from co_pessoa                               a
                left outer     join  co_pessoa_fisica  b on (a.sq_pessoa      = b.sq_pessoa)
                left outer     join  sg_autenticacao   c on (a.sq_pessoa      = c.sq_pessoa)
                    left outer join eo_unidade         f on (c.sq_unidade     = f.sq_unidade)
                    left outer join eo_localizacao     g on (c.sq_localizacao = g.sq_localizacao),
               co_tipo_vinculo  d,
               co_tipo_pessoa   e
         where a.sq_tipo_vinculo = d.sq_tipo_vinculo
           and d.interno         = 'S'
           and a.sq_tipo_pessoa  = e.sq_tipo_pessoa
           and e.ativo           = 'S'
           and e.nome            = 'F�sica'
           and a.sq_pessoa_pai   = p_cliente 
           and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or   a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
           and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))           
      order by a.nome_indice;
   Elsif p_restricao = 'USUARIOS' Then
      -- Recupera os usu�rios do sistema
      open p_result for 
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla as sg_unidade, f.nome as nm_unidade, g.nome as nm_local
          from co_pessoa                           a
                left outer join  co_pessoa_fisica  b on (a.sq_pessoa       = b.sq_pessoa)
                inner      join  sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa)
                  inner    join  eo_unidade        f on (c.sq_unidade      = f.sq_unidade)
                  inner    join  eo_localizacao    g on (c.sq_localizacao  = g.sq_localizacao)
                inner      join co_tipo_vinculo    d on (a.sq_tipo_vinculo = d.sq_tipo_vinculo)
                inner      join co_tipo_pessoa     e on (a.sq_tipo_pessoa  = e.sq_tipo_pessoa)
         where c.ativo          = 'S'
           and d.interno        = 'S'
           and e.ativo          = 'S'
           and e.nome           = 'F�sica'
           and a.sq_pessoa_pai  = p_cliente 
           and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or   a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
           and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))           
      order by a.nome_indice;
   Elsif p_restricao = 'TTCENTRAL' Then
      -- Recupera as pessoas vinculadas a uma central telef�nica
      open p_result for 
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa       = b.usuario)
                    inner      join tt_central        f on (b.sq_central_fone = f.sq_central_fone and
                                                            f.sq_central_fone = p_chave)
          where a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))          
         order by a.nome_resumido_ind;
   Elsif p_restricao = 'TTTRANSFERE' Then
      -- Recupera as pessoas vinculadas a uma central telef�nica
      open p_result for 
         select distinct a.sq_pessoa, a.nome_resumido, a.nome, a.nome_resumido_ind,
                f.sigla as sg_unidade, f.nome as nm_unidade, g.nome as nm_local
           from co_pessoa                      a
                inner    join tt_usuario       b on (a.sq_pessoa             = b.usuario)
                   inner join tt_central       c on (b.sq_central_fone       = c.sq_central_fone)
                   inner join tt_ramal_usuario d on (b.sq_usuario_central    = d.sq_usuario_central)
                inner    join sg_autenticacao  e on (a.sq_pessoa             = e.sq_pessoa)
                   inner join eo_unidade       f on (e.sq_unidade            = f.sq_unidade)
                   inner join eo_localizacao   g on (e.sq_localizacao        = g.sq_localizacao)
          where a.sq_pessoa_pai         = p_cliente
            and c.sq_central_fone       = p_chave
            and d.fim                   is null
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))            
         order by a.nome_resumido_ind;
   Elsif p_restricao = 'TTUSUCENTRAL' Then
      -- Recupera os usu�rios do sistema que ainda n�o est�o vinculados � central informada
      open p_result for 
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 inner         join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    inner      join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    inner      join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
          where a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))          
         EXCEPT
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa       = b.usuario)
                    inner      join tt_central        f on (b.sq_central_fone = f.sq_central_fone and
                                                            f.sq_central_fone = p_chave)
          where a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))          
         order by nome_resumido_ind;
   Elsif p_restricao = 'TTUSURAMAL' Then
      -- Recupera os usu�rios do sistema que ainda n�o est�o vinculados � central informada
      open p_result for 
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 inner         join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    inner      join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    inner      join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                inner          join tt_usuario        f on (a.sq_pessoa       = f.usuario)
          where a.sq_pessoa_pai   = p_cliente
            and c.ativo           = 'S'
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))          
         EXCEPT
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla as sg_unidade, d.nome as nm_unidade, e.nome as nm_local
           from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa          = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade         = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao     = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa          = b.usuario)
                    inner      join tt_ramal_usuario  f on (b.sq_usuario_central = f.sq_usuario_central and
                                                            f.sq_ramal           = p_chave and
                                                            f.fim                is null)
          where a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla, null) like '%'||acentos(p_sg_unidade, null)||'%'))          
         order by nome_resumido_ind;
   Else
      Loop
         l_item  := Trim(substr(l_tipo,1,Instr(l_tipo,',')-1));
         If Length(l_item) > 0 Then
            x_tipo := x_tipo||','''||l_item||'''';
         End If;
         l_tipo := substr(l_tipo,Instr(l_tipo,',')+1,200);
         Exit when l_tipo is null;
      End Loop;
      x_tipo := upper(substr(x_tipo,2,200));

      -- Recupera os usu�rios do sistema que estiverem nos v�nculos informados
      open p_result for 
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla as sg_unidade, f.nome as nm_unidade, g.nome as nm_local
          from co_pessoa                           a
                left outer join  co_pessoa_fisica  b on (a.sq_pessoa       = b.sq_pessoa)
                inner      join  sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa and
                                                         c.ativo           = 'S'
                                                        )
                  inner    join  eo_unidade        f on (c.sq_unidade      = f.sq_unidade)
                  inner    join  eo_localizacao    g on (c.sq_localizacao  = g.sq_localizacao)
                inner      join co_tipo_vinculo    d on (a.sq_tipo_vinculo = d.sq_tipo_vinculo and
                                                         d.interno         = 'S' and
                                                         0                 < InStr(x_tipo,''''||upper(d.nome)||'''')
                                                        )
                inner      join co_tipo_pessoa     e on (a.sq_tipo_pessoa  = e.sq_tipo_pessoa and
                                                         e.ativo           = 'S' and
                                                         e.nome            = 'F�sica'
                                                        )
         where a.sq_pessoa_pai  = p_cliente 
           and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome, null))||'%')
                                                                   or   a.nome_resumido_ind like '%'||upper(acentos(p_nome, null))||'%')))
           and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla, null) like '%'||(p_sg_unidade, null)||'%'))         
      order by a.nome_indice;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;