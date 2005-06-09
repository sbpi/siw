create or replace procedure SP_GetFormatList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select ordem, sq_formacao, nome, ativo,
             decode(tipo,'1','Acadêmica','2','Técnica','Prod.Cient.') tipo,
             decode(ativo,'S','Sim','Não') ativodesc
        from co_formacao;
end SP_GetFormatList;
/

