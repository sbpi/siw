create or replace procedure SP_GetFuncRel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_bairro       in varchar2 default null,
    p_unidade      in number   default null,
    p_area         in number   default null,
    p_escolaridade in varchar2 default null,
    p_cargo        in varchar2 default null,
    p_sexo         in varchar2 default null,
    p_mat_ini      in date     default null,
    p_mat_fim      in date     default null,
    p_result    out sys_refcursor) is
begin
   If p_regional = 0 Then
      -- Recupera os alunos por período
      open p_result for
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
           from s_funcionario  a,
                s_unidadefunc  b,
                s_cargo        c,
                s_area_atuacao d,
                s_unidade      e,
                s_escola       f
          where a.co_funcionario  = b.co_funcionario (+)
            and a.co_unidade      = b.co_unidade     (+)
            and a.co_unidade      = e.co_unidade     (+)
            and b.co_cargo        = c.co_cargo       (+)
            and b.co_area_atuacao = d.co_area_atuacao(+)
            and a.co_unidade      = f.co_unidade     (+)
            and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
            and (p_area         is null or (p_area         is not null and d.co_area_atuacao    = p_area))
            and (p_escolaridade is null or (p_escolaridade is not null and trim(a.ds_instrucao) = p_escolaridade))
            and (p_cargo        is null or (p_cargo        is not null and trim(c.co_cargo)           = p_cargo))
            and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
            and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
            and ((p_bairro is null) or
                 (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )
            and b.ano_sem          = p_periodo;
   Else
      -- Recupera os alunos por período e regional
      open p_result for
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade,e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
           from s_funcionario  a,
                s_unidadefunc  b,
                s_cargo        c,
                s_area_atuacao d,
                s_unidade      e,
                s_escola       f
          where a.co_funcionario  = b.co_funcionario   (+)
            and a.co_unidade      = b.co_unidade       (+)
            and a.co_unidade      = e.co_unidade       (+)
            and b.co_cargo        = c.co_cargo         (+)
            and b.co_area_atuacao = d.co_area_atuacao  (+)
            and a.co_unidade      = f.co_unidade       (+)
            and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
            and (p_area         is null or (p_area         is not null and d.co_area_atuacao    = p_area))
            and (p_escolaridade is null or (p_escolaridade is not null and trim(a.ds_instrucao) = p_escolaridade))
            and (p_cargo        is null or (p_cargo        is not null and trim(c.co_cargo)           = p_cargo))
            and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
            and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
            and ((p_bairro is null) or
                 (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )
            and b.ano_sem         = p_periodo
            and f.co_sigre         like p_regional||'%';
   End If;
end SP_GetFuncRel;
/

