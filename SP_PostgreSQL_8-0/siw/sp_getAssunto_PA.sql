create or replace function siw.sp_getAssunto_PA
   (p_cliente          numeric,
    p_chave            numeric,
    p_chave_pai        numeric,
    p_codigo           varchar,
    p_descricao        varchar,
    p_corrente_guarda  numeric,
    p_intermed_guarda  numeric,
    p_final_guarda     numeric,
    p_destinacao_final numeric,
    p_ativo            varchar,
    p_restricao        varchar)

  RETURNS character varying AS
$BODY$declare

    
    p_result          refcursor;
begin
   If p_restricao = 'REGISTROS' or p_restricao = 'SUBGRUPO' Then
      -- Recupera os assuntos existentes
      open p_result for
         select a.sq_assunto, a.sq_assunto as chave, a.cliente, a.sq_assunto_pai, a.codigo, a.tipo, a.descricao, a.detalhamento,
                a.observacao, a.fase_corrente_guarda, a.fase_corrente_anos, a.fase_intermed_guarda,
                a.fase_intermed_anos, a.fase_final_guarda, a.fase_final_anos, a.destinacao_final, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.descricao as ds_corrente_guarda, b.sigla as sg_corrente_guarda,
                c.descricao as ds_intermed_guarda, c.sigla as sg_intermed_guarda,
                d.descricao as  ds_final_guarda, d.sigla as sg_final_guarda,
                e.descricao as ds_destinacao_final, e.sigla, sg_destina as cao_final,
                f.codigo as cd_assunto_pai, f.descricao as ds_assunto_pai,
                g.codigo as cd_assunto_avo, g.descricao as ds_assunto_avo,
                h.codigo as cd_assunto_bis, h.descricao as ds_assunto_bis
           from siw.pa_assunto                    a
                inner     join siw.pa_tipo_guarda b on (a.fase_corrente_guarda = b.sq_tipo_guarda)
                inner     join siw.pa_tipo_guarda c on (a.fase_intermed_guarda = c.sq_tipo_guarda)
                inner     join siw.pa_tipo_guarda d on (a.fase_final_guarda    = d.sq_tipo_guarda)
                inner     join siw.pa_tipo_guarda e on (a.destinacao_final     = e.sq_tipo_guarda)
                left      join siw.pa_assunto     f on (a.sq_assunto_pai       = f.sq_assunto)
                  left    join siw.pa_assunto     g on (f.sq_assunto_pai       = g.sq_assunto)
                    left  join siw.pa_assunto     h on (g.sq_assunto_pai       = h.sq_assunto)
          where a.cliente           = p_cliente
            and (p_chave            is null or (p_chave            is not null and a.sq_assunto     = p_chave))
            and (p_chave_pai        is null or (p_chave_pai        is not null and a.sq_assunto_pai = p_chave_pai))
            and (p_descricao        is null or (p_descricao        is not null and (acentos(a.descricao)    like '%'||acentos(p_descricao)||'%' or
                                                                                    acentos(a.detalhamento) like '%'||acentos(p_descricao)||'%' or
                                                                                    acentos(a.observacao)   like '%'||acentos(p_descricao)||'%'
                                                                                   )
                                               )
                )
            and (p_corrente_guarda  is null or (p_corrente_guarda  is not null and a.fase_corrente_guarda = p_corrente_guarda))
            and (p_intermed_guarda  is null or (p_intermed_guarda  is not null and a.fase_intermed_guarda = p_intermed_guarda))
            and (p_final_guarda     is null or (p_final_guarda     is not null and a.fase_final_guarda = p_final_guarda))
            and (p_destinacao_final is null or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
            and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo))
            and (p_codigo           is null or (p_codigo           is not null and a.codigo like '%'||p_codigo||'%'))
            and (p_restricao        is null or (p_restricao        <> 'SUBGRUPO' or (p_restricao = 'SUBGRUPO' and a.tipo <> '4 - Subgrupo')));
   Elsif upper(p_restricao) = 'FOLHA' Then
     -- Recupera apenas os registros sem filhos
      open p_result for
         select a.sq_assunto as chave, a.sq_assunto_pai, a.codigo, a.descricao, a.tipo
           from siw.pa_assunto a
                left  join (select sq_assunto_pai
                              from pa_assunto
                            group by sq_assunto_pai
                           )    b on (a.sq_assunto = b.sq_assunto_pai)
          where a.cliente           = p_cliente
            and b.sq_assunto_pai    is null
            and (p_chave            is null or (p_chave            is not null and a.sq_assunto = p_chave))
            and (p_corrente_guarda  is null or (p_corrente_guarda  is not null and a.fase_corrente_guarda = p_corrente_guarda))
            and (p_intermed_guarda  is null or (p_intermed_guarda  is not null and a.fase_intermed_guarda = p_intermed_guarda))
            and (p_final_guarda     is null or (p_final_guarda     is not null and a.fase_final_guarda = p_final_guarda))
            and (p_destinacao_final is null or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
            and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo))
            and (p_codigo           is null or (p_codigo           is not null and a.codigo like '%'||p_codigo||'%'))
            and (p_descricao        is null or (p_descricao        is not null and acentos(a.descricao) like '%'||acentos(p_descricao)||'%'))
         connect by prior a.sq_assunto_pai = a.sq_assunto
         order by 5;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for
         select a.sq_assunto as chave, a.cliente, a.descricao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw.pa_assunto a
          where a.cliente                = p_cliente
            and a.sq_assunto            <> coalesce(p_chave,0)
            and (p_descricao        is null or (p_descricao        is not null and a.descricao like '%'||p_descricao||'%'))
            and (p_corrente_guarda  is null or (p_corrente_guarda  is not null and a.fase_corrente_guarda = p_corrente_guarda))
            and (p_intermed_guarda  is null or (p_intermed_guarda  is not null and a.fase_intermed_guarda = p_intermed_guarda))
            and (p_final_guarda     is null or (p_final_guarda     is not null and a.fase_final_guarda = p_final_guarda))
            and (p_destinacao_final is null or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
            and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo));
   Elsif p_restricao = 'BUSCA' Then
      -- Recupera os assuntos existentes
      open p_result for
         select a.sq_assunto, a.sq_assunto as chave, a.cliente, a.sq_assunto_pai, a.codigo, a.tipo, a.descricao, a.detalhamento,
                a.observacao, a.fase_corrente_guarda, a.fase_corrente_anos, a.fase_intermed_guarda,
                a.fase_intermed_anos, a.fase_final_guarda, a.fase_final_anos, a.destinacao_final, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.descricao as ds_corrente_guarda, b.sigla sg_corrente_guarda,
                c.descricao as ds_intermed_guarda, c.sigla as sg_intermed_guarda,
                d.descricao as ds_final_guarda, d.sigla as  sg_final_guarda,
                e.descricao as ds_destinacao_final, e.sigla as sg_destinacao_final,
                f.codigo as cd_assunto_pai, f.descricao as ds_assunto_pai,
                g.codigo as cd_assunto_avo, g.descricao as ds_assunto_avo,
                h.codigo as cd_assunto_bis, h.descricao as ds_assunto_bis
           from siw.pa_assunto                    a
                inner     join pa_tipo_guarda b on (a.fase_corrente_guarda = b.sq_tipo_guarda)
                inner     join pa_tipo_guarda c on (a.fase_intermed_guarda = c.sq_tipo_guarda)
                inner     join pa_tipo_guarda d on (a.fase_final_guarda    = d.sq_tipo_guarda)
                inner     join pa_tipo_guarda e on (a.destinacao_final     = e.sq_tipo_guarda)
                left      join pa_assunto     f on (a.sq_assunto_pai       = f.sq_assunto)
                  left    join pa_assunto     g on (f.sq_assunto_pai       = g.sq_assunto)
                    left  join pa_assunto     h on (g.sq_assunto_pai       = h.sq_assunto)
          where a.cliente           = p_cliente
            and a.tipo              = '4 - Subgrupo'
            and (p_descricao        is null or (p_descricao        is not null and (acentos(a.descricao)    like '%'||acentos(p_descricao)||'%' or
                                                                                    acentos(a.detalhamento) like '%'||acentos(p_descricao)||'%' or
                                                                                    acentos(a.observacao)   like '%'||acentos(p_descricao)||'%'
                                                                                   )
                                               )
                )
            and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo))
            and (p_codigo           is null or (p_codigo           is not null and a.codigo like '%'||p_codigo||'%'));
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro está vinculado a um documento
      open p_result for
         select a.sq_assunto as chave
           from siw.pa_assunto                      a
                inner join siw.pa_documento_assunto b on (a.sq_assunto = b.sq_assunto)
          where a.cliente    = p_cliente
            and a.sq_assunto = p_chave;
   Elsif p_restricao is not null Then
      If upper(p_restricao) = 'ISNULL' Then
         open p_result for
            select a.sq_assunto as chave, a.cliente, a.sq_assunto_pai, a.codigo, a.tipo, a.descricao,
                   a.detalhamento, a.observacao, a.fase_corrente_guarda, a.fase_corrente_anos,
                   a.fase_intermed_guarda, a.fase_intermed_anos, a.fase_final_guarda, a.fase_final_anos,
                   a.destinacao_final, a.ativo,
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   coalesce(b.filho,0) as filho,
                   coalesce(c.qtd,0) as qt_assuntos,
                   d.descricao as ds_corrente_guarda, d.sigla as sg_corrente_guarda,
                   e.descricao as ds_intermed_guarda, e.sigla as sg_intermed_guarda,
                   f.descricao as ds_final_guarda, f.sigla as sg_final_guarda,
                   g.descricao as ds_destinacao_final, g.sigla as sg_destinacao_final
              from siw.pa_assunto   a
                   left  join (select sq_assunto_pai, count(sq_assunto) as filho
                                 from pa_assunto x
                                where cliente = p_cliente
                               group by sq_assunto_pai
                              ) b on (a.sq_assunto = b.sq_assunto_pai)
                   left  join (select x.sq_assunto, count(x.sq_siw_solicitacao) qtd
                                 from pa_documento_assunto x
                               group by x.sq_assunto
                              ) c on (a.sq_assunto = c.sq_assunto)
                   inner join siw.pa_tipo_guarda d on (a.fase_corrente_guarda = d.sq_tipo_guarda)
                   inner join siw.pa_tipo_guarda e on (a.fase_intermed_guarda = e.sq_tipo_guarda)
                   inner join siw.pa_tipo_guarda f on (a.fase_final_guarda    = f.sq_tipo_guarda)
                   inner join siw.pa_tipo_guarda g on (a.destinacao_final     = g.sq_tipo_guarda)

             where a.cliente        = p_cliente
               and a.sq_assunto_pai is null
               and (p_descricao        is null or (p_descricao        is not null and a.descricao like '%'||p_descricao||'%'))
               and (p_corrente_guarda  is null or (p_corrente_guarda  is not null and a.fase_corrente_guarda = p_corrente_guarda))
               and (p_intermed_guarda  is null or (p_intermed_guarda  is not null and a.fase_intermed_guarda = p_intermed_guarda))
               and (p_final_guarda     is null or (p_final_guarda     is not null and a.fase_final_guarda = p_final_guarda))
               and (p_destinacao_final is null or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
               and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo));
      Else
         open p_result for
            select a.sq_assunto as chave, a.cliente, a.sq_assunto_pai, a.descricao, a.ativo, coalesce(b.filho,0) as filho,
                coalesce(c.qtd,0) as qt_assuntos
              from siw.pa_assunto a
                   left join (select sq_assunto_pai, count(sq_assunto) as filho
                                from pa_assunto x
                               where cliente = p_cliente
                              group by sq_assunto_pai
                             ) b on (a.sq_assunto = b.sq_assunto_pai)
                   left  join (select x.sq_assunto, count(x.sq_siw_solicitacao) qtd
                                 from pa_documento_assunto x
                               group by x.sq_assunto
                              ) c on (a.sq_assunto = c.sq_assunto)
             where a.cliente     = p_cliente
               and (p_descricao        is null or (p_descricao        is not null and a.descricao like '%'||p_descricao||'%'))
               and (p_corrente_guarda  is null or (p_corrente_guarda  is not null and a.fase_corrente_guarda = p_corrente_guarda))
               and (p_intermed_guarda  is null or (p_intermed_guarda  is not null and a.fase_intermed_guarda = p_intermed_guarda))
               and (p_final_guarda     is null or (p_final_guarda     is not null and a.fase_final_guarda = p_final_guarda))
               and (p_destinacao_final is null or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
               and (p_ativo            is null or (p_ativo            is not null and a.ativo = p_ativo));
      End If;
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.sp_getAssunto_PA
   (p_cliente          numeric,
    p_chave            numeric,
    p_chave_pai        numeric,
    p_codigo           varchar,
    p_descricao        varchar,
    p_corrente_guarda  numeric,
    p_intermed_guarda  numeric,
    p_final_guarda     numeric,
    p_destinacao_final numeric,
    p_ativo            varchar,
    p_restricao        varchar) OWNER TO siw;
