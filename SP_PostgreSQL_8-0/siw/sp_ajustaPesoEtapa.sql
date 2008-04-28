CREATE OR REPLACE FUNCTION siw.sp_ajustaPesoEtapa(p_projeto numeric,p_todos character varying)
  RETURNS character varying AS
$BODY$declare

  w_existe    numeric(18);
  w_inicio    date ;

begin


  select count(sq_siw_solicitacao) into w_existe from siw.pj_projeto where sq_siw_solicitacao = coalesce(p_projeto,0);
  If (w_existe = 0) and (coalesce(p_todos,'nulo') <> 'TODOS') Then
     return Result;
  Elsif coalesce(p_todos,'nulo') = 'TODOS' Then
     -- Atualiza as datas de todos os projetos
    for c_projetos in   select a.sq_siw_solicitacao
           from siw.pj_projeto a
                inner join siw.pj_projeto_etapa b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)  loop
        siw.sp_ajustadataEtapa(c_projetos.sq_siw_solicitacao);
     end loop;
  End If;

  
  return Result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
