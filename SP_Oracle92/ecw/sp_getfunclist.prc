create or replace procedure SP_GetFuncList
   (p_periodo     in number,
    p_regional    in varchar2 default null,
    p_cpf         in varchar2 default null,
    p_cargo       in char     default null,
    p_matricula   in char     default null,
    p_unidade     in number   default null,
    p_funcionario in varchar2 default null,
    p_prof        in char     default null,
    p_canc        in char     default null,
    p_result      out sys_refcursor) is
begin
    -- Recupera os funcionario por período e regional
    open p_result for
       select distinct
              a.co_funcionario, a.nu_matricula_mec, a.ds_funcionario, a.nu_cpf,
              b.co_cargo, d.ds_cargo, c.co_unidade, c.ds_escola,
              b.id_professor, b.st_cancelado
         from s_funcionario      a,
              s_unidadefunc      b,
              s_escola           c,
              s_cargo            d
        where a.co_funcionario     = b.co_funcionario (+)
          and b.co_unidade         = c.co_unidade (+)
          and b.co_cargo           = d.co_cargo (+)
          and b.ano_sem            = p_periodo
          and (p_regional          = 0 or (p_regional > 0 and c.co_sigre like p_regional||'%'))
          and (p_cpf               is null or (p_cpf is not null and a.nu_cpf = p_cpf))
          and (p_cargo             is null or (p_cargo is not null and b.co_cargo = p_cargo))
          and (p_matricula         is null or (p_matricula is not null and a.nu_matricula_mec = p_matricula))
          and (p_unidade           is null or (p_unidade is not null and c.co_unidade = p_unidade))
          and (p_funcionario       is null or (p_funcionario is not null and a.ds_funcionario like p_funcionario))
          and (p_prof              is null or (p_prof is not null and Nvl(b.id_professor,'N') = p_prof))
          and (p_canc              is null or (p_canc is not null and Nvl(b.st_cancelado,'N') = p_canc));
end SP_GetFuncList;
/

