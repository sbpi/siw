create or replace function siw.SP_GetEtpDataPrnts (p_chave in  numeric, p_result refcursor) returns refcursor as $$
begin
   -- Recupera as etapas acima da informada
   open p_result for 
      select sq_projeto_etapa, sq_etapa_pai, titulo, ordem
        from pj_projeto_etapa
      where sq_projeto_etapa in (select sq_projeto_etapa from sp_fGetEtapaList(p_chave,0,'UP'));
   return p_result;
end; $$ language 'plpgsql' volatile;