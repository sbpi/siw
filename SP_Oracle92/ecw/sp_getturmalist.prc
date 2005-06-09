create or replace procedure SP_GetTurmaList
   (p_periodo         in  number,
    p_unidade         in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da turma
   open p_result for
      select a.co_turma, a.co_curso, a.co_seq_serie, a.co_letra_turma, a.ds_turma,
             a.co_turma_procura, b.sg_serie, d.co_tipo_curso, d.sg_tipo_curso
        from s_turma       a,
             s_curso_serie b,
             s_curso       c,
             s_tipo_curso  d
       where a.co_curso      = b.co_curso
         and a.ano_sem       = b.ano_sem
         and a.co_unidade    = b.co_unidade
         and a.co_seq_serie  = b.co_seq_serie
         and b.co_curso      = c.co_curso
         and b.co_unidade    = c.co_unidade
         and b.ano_sem       = c.ano_sem
         and c.co_tipo_curso = d.co_tipo_curso
         and a.co_unidade    = p_unidade
         and a.ano_sem       = p_periodo;
end SP_GetTurmaList;
/

