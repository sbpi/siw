create or replace FUNCTION sp_getTipoMatServ
   (p_cliente   numeric,
    p_chave     numeric,
    p_chave_pai numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_gestora   numeric,
    p_ativo     varchar,
    p_classe    numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'REGISTROS' Then
      -- Recupera os tipos de material e serviços existentes
      open p_result for 
         select a.sq_tipo_material as chave, a.cliente, a.sq_tipo_pai, a.unidade_gestora, a.nome, a.sigla, 
                a.descricao, a.codigo_externo, a.ativo, a.classe,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(a.sq_tipo_material) as nome_completo,
                b.nome nm_unidade, b.sigla sg_unidade
           from cl_tipo_material      a
                inner join eo_unidade b on (a.unidade_gestora = b.sq_unidade)
          where a.cliente            = p_cliente
            and (p_chave             is null or (p_chave   is not null and a.sq_tipo_material = p_chave))
            and (p_nome              is null or (p_nome    is not null and a.nome = p_nome))
            and (p_gestora           is null or (p_gestora is not null and a.unidade_gestora = p_gestora))
            and (p_classe            is null or (p_classe is not null and a.classe = p_classe))
            and (p_sigla             is null or (p_sigla   is not null and a.sigla = upper(p_sigla)))
            and (p_ativo             is null or (p_ativo   is not null and a.ativo = p_ativo))
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBTODOS' Then
     -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_tipo_material chave,a.nome, a.codigo_externo, a.classe,
                montanometipomaterial(a.sq_tipo_material) as nome_completo,
                case a.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                coalesce(b.qtd,0) as qt_materiais
           from cl_tipo_material  a
                left  join (select x.sq_tipo_material, count(x.sq_material) qtd 
                              from cl_material x
                            group by x.sq_tipo_material
                           )      b on (a.sq_tipo_material = b.sq_tipo_material)
          where a.cliente = p_cliente
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBPARTE' Then
     -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_tipo_material chave,a.nome, a.codigo_externo, a.classe,
                montanometipomaterial(a.sq_tipo_material) as nome_completo,
                case a.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                coalesce(b.qtd,0) as qt_materiais
           from cl_tipo_material   a
                left  join (select x.sq_tipo_material, count(x.sq_material) qtd 
                              from cl_material x
                            group by x.sq_tipo_material
                           )      b on (a.sq_tipo_material = b.sq_tipo_material)
          where a.cliente = p_cliente
            and a.sq_tipo_material not in (select sq_tipo_material from connectby('cl_tipo_material','sq_tipo_material','sq_tipo_pai',to_char(p_chave),'0') as (sq_tipo_material numeric,sq_tipo_pai numeric, level int))
         order by a.nome;
   Elsif upper(p_restricao) = 'FOLHA' Then
     -- Recupera apenas os registros sem filhos
      open p_result for
         select a.sq_tipo_material as chave, a.sq_tipo_pai, a.nome, a.sigla, a.codigo_externo, a.classe,
                montanometipomaterial(a.sq_tipo_material, 'inverso') as nome_completo,
                case a.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe
           from cl_tipo_material a
                left  join (select sq_tipo_pai
                              from cl_tipo_material 
                            group by sq_tipo_pai
                           )    b on (a.sq_tipo_material = b.sq_tipo_pai)
          where a.cliente     = p_cliente
            and b.sq_tipo_pai is null
            and (p_chave      is null or (p_chave     is not null and a.sq_tipo_material = p_chave))
            and (p_classe     is null or (p_classe    is not null and a.classe           = p_classe))
            and (p_nome       is null or (p_nome      is not null and a.nome             = p_nome))
            and (p_gestora    is null or (p_gestora   is not null and a.unidade_gestora  = p_gestora))
            and (p_sigla      is null or (p_sigla     is not null and a.sigla            = upper(p_sigla)))
            and (p_ativo      is null or (p_ativo     is not null and ((a.ativo          = 'S' and 0 = (select sum(case ativo when 'S' then 0 else 1 end)
                                                                                                          from cl_tipo_material
                                                                                                         where sq_tipo_material in (select sq_tipo_material from connectby('cl_tipo_material','sq_tipo_pai','sq_tipo_material',to_char(a.sq_tipo_material),'0') as (sq_tipo_material numeric,sq_tipo_pai numeric, level int))
                                                                                                       )
                                                                       ) or
                                                                       (a.ativo          = 'N' and 0 < (select sum(case ativo when 'S' then 0 else 1 end)
                                                                                                          from cl_tipo_material
                                                                                                         where sq_tipo_material in (select sq_tipo_material from connectby('cl_tipo_material','sq_tipo_pai','sq_tipo_material',to_char(a.sq_tipo_material),'0') as (sq_tipo_material numeric,sq_tipo_pai numeric, level int))
                                                                                                       )
                                                                       )
                                                                      )
                                         )
                )
         order by 7;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select b.sq_tipo_material as chave, b.cliente, b.nome, 
                b.sigla, b.descricao, b.ativo, b.codigo_externo, b.classe,
                case b.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case b.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe
           from cl_tipo_material            a
                inner join cl_tipo_material b on (a.sq_tipo_pai = b.sq_tipo_pai)
          where a.cliente            = p_cliente
            and a.sq_tipo_material   = coalesce(p_chave,0)
            and b.sq_tipo_material   <> coalesce(p_chave,0)
            and (p_nome              is null or (p_nome    is not null and acentos(b.nome) = acentos(p_nome)))
            and (p_gestora           is null or (p_gestora is not null and b.unidade_gestora = p_gestora))
            and (p_classe            is null or (p_classe is not null and b.classe = p_classe))
            and (p_sigla             is null or (p_sigla   is not null and acentos(b.sigla) = acentos(p_sigla)))
            and (p_ativo             is null or (p_ativo   is not null and b.ativo = p_ativo))
         order by a.nome;
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro está vinculado a um material ou serviço
      open p_result for 
         select a.sq_tipo_material as chave, a.cliente, a.nome,
                a.sigla, a.descricao, a.ativo, a.codigo_externo, a.classe,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe
           from cl_tipo_material                a
                inner join cl_material          b on (a.sq_tipo_material = b.sq_tipo_material)
          where a.cliente                = p_cliente
            and a.sq_tipo_material  = p_chave
         order by a.nome;
   Elsif p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_tipo_material as chave, a.cliente, a.sq_tipo_pai, a.nome, a.sigla, 
                   a.descricao, a.ativo, a.codigo_externo, a.classe,
                   montanometipomaterial(a.sq_tipo_material) as nome_completo,
                   case a.classe
                        when 1 then 'Medicamento'
                        when 3 then 'Consumo'
                        when 4 then 'Permanente'
                        when 5 then 'Serviço'
                   end as nm_classe,
                   coalesce(b.filho,0) as filho,
                   coalesce(c.qtd,0) as qt_materiais
              from cl_tipo_material a
                   left  join (select sq_tipo_pai, count(sq_tipo_material) as filho 
                                 from cl_tipo_material x 
                                where cliente = p_cliente 
                               group by sq_tipo_pai
                              ) b on (a.sq_tipo_material = b.sq_tipo_pai)
                   left  join (select x.sq_tipo_material, count(x.sq_material) qtd 
                                 from cl_material x
                               group by x.sq_tipo_material
                              ) c on (a.sq_tipo_material = c.sq_tipo_material)
             where a.cliente     = p_cliente
               and a.sq_tipo_pai is null
               and (p_nome       is null or (p_nome    is not null and a.nome   = p_nome))
               and (p_gestora    is null or (p_gestora is not null and a.unidade_gestora = p_gestora))
               and (p_classe     is null or (p_classe is not null and a.classe = p_classe))
               and (p_ativo      is null or (p_ativo   is not null and a.ativo = p_ativo))
            order by a.sigla, a.nome;
      Else
         open p_result for
            select a.sq_tipo_material as chave, a.cliente, a.sq_tipo_pai, a.nome, a.sigla, 
                   a.descricao, a.ativo, a.codigo_externo, a.classe,
                   montanometipomaterial(a.sq_tipo_material) as nome_completo,
                   case a.classe
                        when 1 then 'Medicamento'
                        when 3 then 'Consumo'
                        when 4 then 'Permanente'
                        when 5 then 'Serviço'
                   end as nm_classe,
                   coalesce(b.filho,0) as filho,
                   coalesce(c.qtd,0) as qt_materiais
              from cl_tipo_material a
                   left join (select sq_tipo_pai, count(sq_tipo_material) as filho 
                                from cl_tipo_material x 
                               where cliente = p_cliente 
                              group by sq_tipo_pai
                             ) b on (a.sq_tipo_material = b.sq_tipo_pai)
                   left  join (select x.sq_tipo_material, count(x.sq_material) qtd 
                                 from cl_material x
                               group by x.sq_tipo_material
                              ) c on (a.sq_tipo_material = c.sq_tipo_material)
             where a.cliente     = p_cliente
               and a.sq_tipo_pai = to_number(p_restricao)
               and (p_nome       is null or (p_nome    is not null and a.nome   = p_nome))
               and (p_gestora    is null or (p_gestora is not null and a.unidade_gestora = p_gestora))
               and (p_classe     is null or (p_classe is not null and a.classe = p_classe))
               and (p_ativo      is null or (p_ativo   is not null and a.ativo = p_ativo))
            order by a.sigla,a.nome;
      End If;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;