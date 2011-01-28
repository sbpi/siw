CREATE OR REPLACE FUNCTION sp_calculaPercEtapa(p_chave numeric, p_pai numeric) RETURNS refcursor AS $$
declare
  w_chave_pai numeric(18) := p_pai;
begin
  If p_chave is not null Then
     select sq_etapa_pai into w_chave_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave;
  End If;
  
  if w_chave_pai is not null then
     update pj_projeto_etapa a set
       a.perc_conclusao = (select coalesce(sum(b.perc_conclusao*b.peso)/(case sum(b.peso) when 0 then count(b.sq_projeto_etapa) else sum(b.peso) end),0)
                             from pj_projeto_etapa            a
                                  inner join pj_projeto_etapa b on (a.sq_projeto_etapa = b.sq_etapa_pai)
                            where a.sq_projeto_etapa = w_chave_pai
                          )
     where a.sq_projeto_etapa = w_chave_pai;
  
  perform sp_calculaPercEtapa(w_chave_pai, null);
  end if;
END $$ LANGUAGE 'plpgsql' VOLATILE;
