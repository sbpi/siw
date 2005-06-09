create or replace procedure SP_GetResponsData
   (p_periodo     in number,
    p_responsavel in varchar2,
    p_result      out sys_refcursor) is
begin
   -- Recupera os alunos por período
   open p_result for
      select distinct c.*, e.ds_tip_responsavel, d.ano_sem,
             a.ds_aluno, a.dt_nascimento nasc_aluno, f.ds_escola,
             e.ds_tip_responsavel, a.co_aluno
        from s_aluno            a,
             s_respons_aluno    b,
             s_responsavel      c,
             s_aluno_per_unid   d,
             s_tipo_responsavel e,
             s_escola           f
        where c.co_responsavel     = b.co_responsavel (+)
          and c.co_unidade         = b.co_unidade (+)
          and b.co_aluno           = d.co_aluno (+)
          and b.co_unidade         = d.co_unidade (+)
          and d.co_aluno           = a.co_aluno (+)
          and d.co_unidade         = f.co_unidade (+)
          and c.co_tip_responsavel = e.co_tip_responsavel
          and d.ano_sem      (+)   = p_periodo
          and c.co_responsavel     = p_responsavel;
end SP_GetResponsData;
/

