create or replace procedure SP_GetStudentList
   (p_periodo     in number,
    p_regional    in varchar2 default null,
    p_aluno       in varchar2 default null,
    p_responsavel in varchar2 default null,
    p_pai         in varchar2 default null,
    p_mae         in varchar2 default null,
    p_matricula   in varchar2 default null,
    p_unidade     in number default null,
    p_cpf         in varchar2 default null,
    p_tipo_resp   in number default null,
    p_result      out sys_refcursor) is
begin
   -- Recupera os alunos por período e regional
   open p_result for
      select distinct
             d.ano_sem, a.co_aluno, a.ds_aluno, c.ds_responsavel,
             f.co_unidade, g.ds_escola, c.co_responsavel, c.nu_cpf,
             h.co_tip_responsavel, h.ds_tip_responsavel, a.ds_pai, a.ds_mae
        from s_aluno            a,
             s_respons_aluno    b,
             s_responsavel      c,
             s_aluno_per_unid   d,
             s_periodounidade   e,
             s_unidade          f,
             s_escola           g,
             s_tipo_responsavel h
       where a.co_aluno           = b.co_aluno
         and b.co_responsavel     = c.co_responsavel
         and b.co_unidade         = c.co_unidade
         and b.co_unidade         = g.co_unidade
         and c.co_tip_responsavel = h.co_tip_responsavel
         and a.co_aluno           = d.co_aluno
         and d.co_unidade         = e.co_unidade
         and d.ano_sem            = e.ano_sem
         and e.co_unidade         = f.co_unidade
         and f.co_unidade         = g.co_unidade
         and e.ano_sem            = p_periodo
         and (p_regional          = 0 or (p_regional > 0 and g.co_sigre like p_regional||'%'))
         and (p_aluno             is null or (p_aluno is not null and a.ds_aluno like p_aluno))
         and (p_responsavel       is null or (p_responsavel is not null and c.ds_responsavel like p_responsavel))
         and (p_pai               is null or (p_pai is not null and a.ds_pai like p_pai))
         and (p_mae               is null or (p_mae is not null and a.ds_mae like p_mae))
         and (p_matricula         is null or (p_matricula is not null and a.co_aluno = lpad(p_matricula,12,' ')))
         and (p_unidade           is null or (p_unidade is not null and g.co_unidade = p_unidade))
         and (p_cpf               is null or (p_cpf is not null and c.nu_cpf = lpad(p_cpf,14,' ')))
         and (p_tipo_resp         is null or (p_tipo_resp is not null and c.Co_Tip_Responsavel = p_tipo_resp));
end SP_GetStudentList;
/

