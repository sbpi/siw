create or replace procedure SP_GetFormatList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select ordem, sq_formacao, nome, ativo,
             decode(tipo,'1','Acad�mica','2','T�cnica','Prod.Cient.') tipo,
             decode(ativo,'S','Sim','N�o') ativodesc
        from co_formacao;
end SP_GetFormatList;
/

