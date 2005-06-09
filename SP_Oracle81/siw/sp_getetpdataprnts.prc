create or replace procedure SP_GetEtpDataPrnts
   (p_chave   in  number,
    p_result  out siw.sys_refcursor
   ) is
begin
   -- Recupera as etapas acima da informada
   open p_result for
      select sq_projeto_etapa, sq_etapa_pai, titulo, ordem
        from pj_projeto_etapa
      start with sq_projeto_etapa   = p_chave
      connect by prior sq_etapa_pai = sq_projeto_etapa;
end SP_GetEtpDataPrnts;
/

