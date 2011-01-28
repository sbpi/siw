CREATE OR REPLACE FUNCTION sp_ajustaDataEtapa(p_projeto numeric,p_todos character varying) RETURNS VOID AS $$
declare
  w_existe    numeric(18);
  w_inicio    date ;
  w_tot       numeric(10);
  w_retorno   varchar(200);
  c_projetos  record;
  c_pacotes   record;
  c_pais      record;
begin
  select count(sq_siw_solicitacao) into w_existe from pj_projeto where sq_siw_solicitacao = coalesce(p_projeto,0);
  If w_existe = 0 and coalesce(p_todos,'nulo') <> 'TODOS' Then
     return NULL;
  Elsif coalesce(p_todos,'nulo') = 'TODOS' Then

     -- Atualiza as datas de todos os projetos
     for c_projetos in  select a.sq_siw_solicitacao
           from pj_projeto a
                inner join pj_projeto_etapa b on a.sq_siw_solicitacao = b.sq_siw_solicitacao loop
        perform sp_ajustadataEtapa(c_projetos.sq_siw_solicitacao);
     end loop;
  end if;
  update pj_projeto_etapa set inicio_real = null, fim_real = null where pacote_trabalho = 'N' and sq_siw_solicitacao = p_projeto;

  for  c_pacotes in select a.sq_etapa_pai, a.inicio_real, a.fim_real
       from pj_projeto_etapa a
      where a.sq_siw_solicitacao = p_projeto
        and a.inicio_real        is not null
        and a.pacote_trabalho    = 'S'  loop
     -- Ajusta a data de início das etapas
     for c_pais in      select a.sq_projeto_etapa
       from  SP_fGetEtapaList_filtro_etapa(p_chave,0,'DOWN',c_pacotes.sq_etapa_pai)
       loop
        update pj_projeto_etapa set inicio_real = c_pacote.inicio_real where (inicio_real is null or inicio_real > c_pacote.inicio_real) and sq_projeto_etapa = c_pai.sq_projeto_etapa;
        if w_inicio is null or w_inicio > c_pacote.inicio_real then w_inicio := c_pacote.inicio_real; end if;
     end loop;

     -- Ajusta a data de término das etapas
      for c_pais in
      select a.sq_projeto_etapa
       from  SP_fGetEtapaList_filtro_etapa(p_chave,0,'DOWN',c_pacotes.sq_etapa_pai)
       loop
        update pj_projeto_etapa  set fim_real = c_pacotes.fim_real 
         where (fim_real is null or fim_real < c_pacote.fim_real) 
         and 0 = (select count(sq_projeto_etapa) from sp_ajustaPesoEtapa_filtra(p_chave,0,'DOWN'))
          and sq_projeto_etapa = c_pai.sq_projeto_etapa;
       end loop;

  -- Reinicializa as datas das etapas que não são pacote de trabalho

     if w_inicio is not null then
        update pj_projeto set inicio_real = w_inicio where sq_siw_solicitacao = p_projeto;
     end if;
   end loop;
end $$ LANGUAGE 'plpgsql' VOLATILE;
