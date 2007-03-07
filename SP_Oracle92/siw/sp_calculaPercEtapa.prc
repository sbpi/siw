create or replace procedure sp_calculaPercEtapa(p_chave in number) is
  w_chave_pai number(18);
begin
  select sq_etapa_pai into w_chave_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave;
  
  if w_chave_pai is not null then
     update pj_projeto_etapa a set
       a.perc_conclusao = (select sum(b.perc_conclusao*b.peso)/(case sum(b.peso) when 0 then count(b.sq_projeto_etapa) else sum(b.peso) end)
                             from pj_projeto_etapa            a
                                  inner join pj_projeto_etapa b on (a.sq_projeto_etapa = b.sq_etapa_pai)
                            where a.sq_projeto_etapa = w_chave_pai
                          )
     where a.sq_projeto_etapa = w_chave_pai;
  
     sp_calculaPercEtapa(w_chave_pai);
  end if;
end sp_calculaPercEtapa;
/
