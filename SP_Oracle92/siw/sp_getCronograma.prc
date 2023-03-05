create or replace procedure SP_GetCronograma
   (p_chave        in number,
    p_chave_aux    in number   default null,
    p_inicio       in date     default null,
    p_fim          in date     default null,
    p_solic_apoio  in number   default null,
    p_restricao    in varchar2 default null,
    p_result       out sys_refcursor) is
begin
  If p_restricao is null Then
     -- Recupera o cronograma da rubrica
     open p_result for 
           select a.sq_rubrica_cronograma, a.inicio, a.fim,   a.valor_previsto,   a.valor_real, a.quantidade,
                  c.sq_unidade_medida,     c.nome nm_unidade, c.sigla sg_unidade,
                  e.sq_moeda,              e.nome nm_moeda,   e.sigla sg_moeda,   e.simbolo sb_moeda
             from pj_rubrica_cronograma            a
                  inner     join pj_rubrica        b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                    left    join co_unidade_medida c on (b.sq_unidade_medida  = c.sq_unidade_medida)
                    inner   join siw_solicitacao   d on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                      left  join co_moeda          e on (d.sq_moeda           = e.sq_moeda)
        where a.sq_projeto_rubrica = p_chave      
          and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_rubrica_cronograma = p_chave_aux))
          and ((p_inicio    is null) or (p_inicio       is not null and ((a.inicio  between p_inicio and p_fim) or
                                                                         (a.fim     between p_inicio and p_fim) or
                                                                         (p_inicio  between a.inicio and a.fim) or
                                                                         (p_fim     between a.inicio and a.fim)
                                                                         )
                                         )
              );
  Elsif p_restricao = 'PERIODO' Then
     -- Recupera o cronograma por período
     open p_result for
       select b.sq_projeto_rubrica,    b.codigo,          b.nome,
              c.sq_unidade_medida,     c.nome nm_unidade, c.sigla sg_unidade,
              e.sq_moeda,              e.nome nm_moeda,   e.sigla sg_moeda,   e.simbolo sb_moeda,
              sum( a.quantidade)  quantidade,
              sum(a.valor_previsto) valor_previsto,
              sum(a.valor_real) valor_real
         from pj_rubrica_cronograma            a
              inner     join pj_rubrica        b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                left    join co_unidade_medida c on (b.sq_unidade_medida  = c.sq_unidade_medida)
                inner   join siw_solicitacao   d on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                  left  join co_moeda          e on (d.sq_moeda           = e.sq_moeda)
        where d.sq_siw_solicitacao = p_chave      
          and ((p_chave_aux is null) or (p_chave_aux    is not null and a.sq_projeto_rubrica = p_chave_aux))
          and ((p_inicio    is null) or (p_inicio       is not null and ((a.inicio  between p_inicio and p_fim) or
                                                                         (a.fim     between p_inicio and p_fim) or
                                                                         (p_inicio  between a.inicio and a.fim) or
                                                                         (p_fim     between a.inicio and a.fim)
                                                                         )
                                         )
              )
       group by b.sq_projeto_rubrica, b.codigo, b.nome,
                c.sq_unidade_medida,  c.nome, c.sigla, e.sq_moeda, e.nome, e.sigla, e.simbolo;
  Elsif p_restricao = 'CADFONTE' Then
     -- Recupera o cronograma da rubrica
     open p_result for 
       select a.sq_rubrica_cronograma, a.inicio, a.fim,   a.valor_previsto,   a.valor_real, a.quantidade,
              c.sq_unidade_medida,     c.nome nm_unidade, c.sigla sg_unidade,
              e.sq_moeda,              e.nome nm_moeda,   e.sigla sg_moeda,   e.simbolo sb_moeda,
              f.valor_previsto vl_fonte_prev,             f.valor_real vl_fonte_real,
              (select sum(valor_previsto)
                 from pj_cronograma_apoio
                where sq_rubrica_cronograma = a.sq_rubrica_cronograma
                  and sq_solic_apoio       <> coalesce(p_solic_apoio,0)
              ) vl_prev_outras
         from pj_rubrica_cronograma              a
              inner     join pj_rubrica          b on (a.sq_projeto_rubrica    = b.sq_projeto_rubrica)
                left    join co_unidade_medida   c on (b.sq_unidade_medida     = c.sq_unidade_medida)
                inner   join siw_solicitacao     d on (b.sq_siw_solicitacao    = d.sq_siw_solicitacao)
                  left  join co_moeda            e on (d.sq_moeda              = e.sq_moeda)
              left      join pj_cronograma_apoio f on (a.sq_rubrica_cronograma = f.sq_rubrica_cronograma and
                                                       f.sq_solic_apoio        = coalesce(p_solic_apoio,0)
                                                      )
        where a.sq_projeto_rubrica = p_chave      
          and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_rubrica_cronograma = p_chave_aux))
          and ((p_inicio    is null) or (p_inicio       is not null and ((a.inicio  between p_inicio and p_fim) or
                                                                         (a.fim     between p_inicio and p_fim) or
                                                                         (p_inicio  between a.inicio and a.fim) or
                                                                         (p_fim     between a.inicio and a.fim)
                                                                         )
                                         )
              );
  Elsif p_restricao = 'RUBFONTES' Then
     -- Recupera o cronograma da rubrica
     open p_result for 
       select distinct a.sq_projeto_rubrica, 
              g.sq_solic_apoio, g.sq_tipo_apoio, g.entidade, g.descricao
         from pj_rubrica_cronograma            a
              inner   join pj_cronograma_apoio f on (a.sq_rubrica_cronograma = f.sq_rubrica_cronograma and
                                                     f.valor_previsto        > 0
                                                    )
                inner join siw_solic_apoio     g on (f.sq_solic_apoio        = g.sq_solic_apoio)
        where a.sq_projeto_rubrica = p_chave
          and (p_chave_aux         is null or (p_chave_aux  is not null and a.sq_rubrica_cronograma = p_chave_aux));
  End If;
end SP_GetCronograma;
/
