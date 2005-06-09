create or replace procedure SP_GetComunicRel
   (p_regional     in varchar2 default null,
    p_unidade      in number   default null,
    p_proc_ini     in date     default null,
    p_proc_fim     in date     default null,
    p_rec_ini      in date     default null,
    p_rec_fim      in date     default null,
    p_result       out sys_refcursor) is
begin
   If p_regional = 0 Then
   open p_result for
      select a.ds_arquivo, a.dt_recebimento, a.dt_process, ds_usuario,
             b.ds_escola, b.ds_gre, a.co_escola, substr(b.co_sigre,1,2) regional,
             a.nu_regional
        from s_comunicacao a,
             s_escola      b
       where a.co_escola   = b.co_unidade
         and (p_unidade    is null or (p_unidade    is not null and a.co_escola      = p_unidade))
         and (p_proc_ini   is null or (p_proc_ini   is not null and a.dt_process     between p_proc_ini and p_proc_fim))
         and (p_rec_ini    is null or (p_rec_ini    is not null and a.dt_recebimento between p_rec_ini and p_rec_fim))
         and b.co_sigre    like p_regional||'%';
    Else
    open p_result for
      select a.ds_arquivo, a.dt_recebimento, a.dt_process, ds_usuario,
             b.ds_escola, b.ds_gre, a.co_escola, substr(b.co_sigre,1,2) regional,
             a.nu_regional
        from s_comunicacao a,
             s_escola      b
       where a.co_escola   = b.co_unidade
         and (p_unidade    is null or (p_unidade    is not null and a.co_escola      = p_unidade))
         and (p_proc_ini   is null or (p_proc_ini   is not null and a.dt_process     between p_proc_ini and p_proc_fim))
         and (p_rec_ini    is null or (p_rec_ini    is not null and a.dt_recebimento between p_rec_ini and p_rec_fim));
    End If;
end SP_GetComunicRel;
/

