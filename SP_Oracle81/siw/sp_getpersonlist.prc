create or replace procedure SP_GetPersonList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_nome       in varchar2 default null,
    p_sg_unidade in varchar2 default null,
    p_codigo     in number   default null,
    p_filhos     in varchar2 default null,    
    p_result    out siw.sys_refcursor) is

  l_item       varchar2(18);
  l_tipo       varchar2(200) := p_restricao ||',';
  x_tipo       varchar2(200) := '';
begin
   If p_restricao = 'PESSOA' or p_restricao = 'NOVOUSO' Then
      -- Recupera as pessoas da organização
      open p_result for
         select a.sq_pessoa, Nvl(b.cpf, c.username) cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                c.username,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 co_pessoa_fisica  b,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e
          where (a.sq_pessoa      = b.sq_pessoa (+))
            and (a.sq_pessoa      = c.sq_pessoa (+))
            and (c.sq_unidade     = d.sq_unidade (+))
            and (c.sq_localizacao = e.sq_localizacao (+))
            and a.sq_pessoa_pai = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))
            and (p_restricao  is null or (p_restricao = 'NOVOUSO' and c.username is null))
         order by a.nome_indice;
   Elsif p_restricao = 'TODOS' Then
      -- Recupera todas as pessoas do cadastro da organização, físicas e jurídicas
      open p_result for
         select a.sq_pessoa, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                b.codigo,
                c.username, c.ativo usuario,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa          a,
                (select x.sq_pessoa, 
                        decode(y.sq_pessoa,null,decode(z.sq_pessoa,null,null,z.cnpj),y.cpf) codigo
                   from co_pessoa          x,
                        co_pessoa_fisica   y,
                        co_pessoa_juridica z
                  where (x.sq_pessoa = y.sq_pessoa (+))
                    and (x.sq_pessoa = z.sq_pessoa (+))
                )                  b,

                sg_autenticacao    c,
                eo_unidade         d,
                eo_localizacao     e
          where (a.sq_pessoa      = b.sq_pessoa (+))
            and (a.sq_pessoa      = c.sq_pessoa (+))
            and (c.sq_unidade     = d.sq_unidade (+))
            and (c.sq_localizacao = e.sq_localizacao (+))
            and (a.sq_pessoa = p_cliente or a.sq_pessoa_pai = p_cliente)
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
            and (p_codigo     is null or (p_codigo     is not null and b.codigo = p_codigo))
         order by a.nome_indice;
   Elsif p_restricao = 'INTERNOS' Then
      -- Recupera as pessoas internas à organização
      open p_result for
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                               a,
                co_pessoa_fisica  b,
                sg_autenticacao   c,
                    eo_unidade         f,
                    eo_localizacao     g,
               co_tipo_vinculo  d,
               co_tipo_pessoa   e
         where (a.sq_pessoa      = b.sq_pessoa (+))
           and (a.sq_pessoa      = c.sq_pessoa (+))
           and (c.sq_unidade     = f.sq_unidade (+))
           and (c.sq_localizacao = g.sq_localizacao (+))
           and a.sq_tipo_vinculo = d.sq_tipo_vinculo
           and d.interno         = 'S'
           and a.sq_tipo_pessoa  = e.sq_tipo_pessoa
           and e.ativo           = 'S'
           and e.nome            = 'Física'
           and a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla) like '%'||acentos(p_sg_unidade)||'%'))                     
      order by a.nome_indice;
   Elsif p_restricao = 'USUARIOS' Then
      -- Recupera os usuários do sistema
      open p_result for
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                           a,
                co_pessoa_fisica  b,
                sg_autenticacao   c,
                  eo_unidade        f,
                  eo_localizacao    g,
                co_tipo_vinculo    d,
                co_tipo_pessoa     e
         where (a.sq_pessoa       = b.sq_pessoa (+))
           and (a.sq_pessoa       = c.sq_pessoa)
           and (c.sq_unidade      = f.sq_unidade)
           and (c.sq_localizacao  = g.sq_localizacao)
           and (a.sq_tipo_vinculo = d.sq_tipo_vinculo)
           and (a.sq_tipo_pessoa  = e.sq_tipo_pessoa)
           and c.ativo          = 'S'
           and d.interno        = 'S'
           and e.ativo          = 'S'
           and e.nome           = 'Física'
           and a.sq_pessoa_pai  = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla) like '%'||acentos(p_sg_unidade)||'%'))                     
      order by a.nome_indice;
   Elsif p_restricao = 'TTCENTRAL' Then
      -- Recupera as pessoas vinculadas a uma central telefônica
      open p_result for
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e,
                 tt_usuario        b,
                    tt_central        f
          where (a.sq_pessoa       = c.sq_pessoa (+))
            and (c.sq_unidade      = d.sq_unidade (+))
            and (c.sq_localizacao  = e.sq_localizacao (+))
            and (a.sq_pessoa       = b.usuario)
            and (b.sq_central_fone = f.sq_central_fone and
                 f.sq_central_fone = p_chave
                )
            and a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         order by a.nome_resumido_ind;
   Elsif p_restricao = 'TTTRANSFERE' Then
      -- Recupera as pessoas vinculadas a uma central telefônica
      open p_result for
         select distinct a.sq_pessoa, a.nome_resumido, a.nome, a.nome_resumido_ind,
                f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
           from co_pessoa                      a,
                tt_usuario       b,
                   tt_central       c,
                   tt_ramal_usuario d,
                sg_autenticacao  e,
                   eo_unidade       f,
                   eo_localizacao   g
          where (a.sq_pessoa             = b.usuario)
            and (b.sq_central_fone       = c.sq_central_fone)
            and (b.sq_usuario_central    = d.sq_usuario_central)
            and (a.sq_pessoa             = e.sq_pessoa)
            and (e.sq_unidade            = f.sq_unidade)
            and (e.sq_localizacao        = g.sq_localizacao)
            and a.sq_pessoa_pai         = p_cliente
            and c.sq_central_fone       = p_chave
            and d.fim                   is null
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         order by a.nome_resumido_ind;
   Elsif p_restricao = 'TTUSUCENTRAL' Then
      -- Recupera os usuários do sistema que ainda não estão vinculados à central informada
      open p_result for
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e
          where (a.sq_pessoa       = c.sq_pessoa)
            and (c.sq_unidade      = d.sq_unidade)
            and (c.sq_localizacao  = e.sq_localizacao)
            and a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         MINUS
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e,
                 tt_usuario        b,
                    tt_central        f
          where (a.sq_pessoa       = c.sq_pessoa (+))
            and (c.sq_unidade      = d.sq_unidade (+))
            and (c.sq_localizacao  = e.sq_localizacao (+))
            and (a.sq_pessoa       = b.usuario)
            and (b.sq_central_fone = f.sq_central_fone and
                 f.sq_central_fone = p_chave
                )
            and a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         order by nome_resumido_ind;
   Elsif p_restricao = 'TTUSURAMAL' Then
      -- Recupera os usuários do sistema que ainda não estão vinculados à central informada
      open p_result for
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e,
                tt_usuario        f
          where (a.sq_pessoa       = c.sq_pessoa)
            and (c.sq_unidade      = d.sq_unidade)
            and (c.sq_localizacao  = e.sq_localizacao)
            and (a.sq_pessoa       = f.usuario)
            and a.sq_pessoa_pai   = p_cliente
            and c.ativo           = 'S'
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         MINUS
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a,
                 sg_autenticacao   c,
                    eo_unidade        d,
                    eo_localizacao    e,
                 tt_usuario        b,
                    tt_ramal_usuario  f
          where (a.sq_pessoa          = c.sq_pessoa (+))
            and (c.sq_unidade         = d.sq_unidade (+))
            and (c.sq_localizacao     = e.sq_localizacao (+))
            and (a.sq_pessoa          = b.usuario)
            and (b.sq_usuario_central = f.sq_usuario_central and
                 f.sq_ramal           = p_chave and
                 f.fim                is null
                )
            and a.sq_pessoa_pai   = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(d.sigla) like '%'||acentos(p_sg_unidade)||'%'))                      
         order by nome_resumido_ind;
         
   ElsIf p_restricao = 'PDUSUARIO' Then
      -- Recupera os usuários do sistema que estiverem cadastrados na PD_UNIDADE
      open p_result for   
        select a.sq_pessoa chave, a.nome_resumido, a.nome_resumido_ind,
               d.sq_unidade, d.sigla sg_unidade, d.nome nm_unidade, 
               d.sigla||'('||e.nome||')' nm_local,
               e.ramal
          from co_pessoa               a,
                 sg_autenticacao       c,
                   eo_unidade          d,
                     eo_localizacao    e,
                 pd_usuario            b
          where (a.sq_pessoa       = c.sq_pessoa (+))
            and (c.sq_unidade      = d.sq_unidade (+))
            and (c.sq_localizacao  = e.sq_localizacao (+))
            and (a.sq_pessoa       = b.sq_pessoa)
            and a.sq_pessoa_pai   = p_cliente
            and (p_chave is null or (p_chave is not null and a.sq_pessoa = p_chave));
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

      -- Recupera os usuários do sistema que estiverem nos vínculos informados
      open p_result for
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                           a,
                co_pessoa_fisica  b,
                sg_autenticacao   c,
                  eo_unidade        f,
                  eo_localizacao    g,
                co_tipo_vinculo    d,
                co_tipo_pessoa     e
         where (a.sq_pessoa       = b.sq_pessoa (+))
           and (a.sq_pessoa       = c.sq_pessoa and
                c.ativo           = 'S'
               )
           and (c.sq_unidade      = f.sq_unidade)
           and (c.sq_localizacao  = g.sq_localizacao)
           and (a.sq_tipo_vinculo = d.sq_tipo_vinculo and
                d.interno         = 'S' and
                0                 < InStr(x_tipo,''''||upper(d.nome)||'''')
               )
           and (a.sq_tipo_pessoa  = e.sq_tipo_pessoa and
                e.ativo           = 'S' and
                e.nome            = 'Física'
               )
           and a.sq_pessoa_pai  = p_cliente
            and (p_nome       is null or (p_nome       is not null and ((a.nome_indice like '%'||upper(acentos(p_nome))||'%')
                                                                   or    a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%')))
            and (p_sg_unidade is null or (p_sg_unidade is not null and acentos(f.sigla) like '%'||acentos(p_sg_unidade)||'%'))                     
      order by a.nome_indice;
   End If;
end SP_GetPersonList;
/
