create or replace procedure SP_GetANEERel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_unidade      in number   default null,
    p_result       out sys_refcursor) is
begin
    open p_result for
       select b.co_unidade, tp_anee, count(*) qtd_anee, ds_escola, ds_gre, substr(a.co_sigre,1,2) regional
         from s_aluno       b,
              s_escola      a
        where b.tp_anee     is not null
          and a.co_unidade = b.co_unidade
          and (p_regional  = 0 or (p_regional > 0 and a.co_sigre like p_regional||'%'))
          and (p_unidade   is null or (p_unidade is not null and a.co_unidade = p_unidade))
        group by b.co_unidade, b.tp_anee, a.ds_escola, a.ds_gre, a.co_sigre;
end SP_GetANEERel;
/

