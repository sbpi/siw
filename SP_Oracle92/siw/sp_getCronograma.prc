create or replace procedure SP_GetCronograma
   (p_chave             in number,
    p_chave_aux         in number default null,
    p_inicio            in date   default null,
    p_fim               in date   default null,    
    p_result    out sys_refcursor) is
begin
   -- Recupera o cronograma da rubrica
   open p_result for 
         select a.sq_rubrica_cronograma, a.inicio, a.fim, a.valor_previsto, a.valor_real, a.quantidade,
                c.sq_unidade_medida, c.nome nm_unidade, c.sigla sg_unidade,
                e.sq_moeda, e.nome nm_moeda, e.sigla sg_moeda, e.simbolo sb_moeda
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
end SP_GetCronograma;
/
