CREATE OR REPLACE FUNCTION sp_ajustaPesoEtapa(p_projeto numeric,p_todos character varying) RETURNS VOID AS $$
declare
  w_existe    numeric(18);
  w_inicio    date ;
declare
  c_projetos cursor for
    select a.sq_siw_solicitacao from pj_projeto a inner join pj_projeto_etapa b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao);
begin
  select count(sq_siw_solicitacao) into w_existe from pj_projeto where sq_siw_solicitacao = coalesce(p_projeto,0);
  If (w_existe = 0) and (coalesce(p_todos,'nulo') <> 'TODOS') Then
     return;
  Elsif coalesce(p_todos,'nulo') = 'TODOS' Then
     -- Atualiza as datas de todos os projetos
    for crec in c_projetos loop
        PERFORM sp_ajustadataEtapa(c_projetos.sq_siw_solicitacao);
     end loop;
  End If;
END $$ LANGUAGE 'plpgsql' VOLATILE;
