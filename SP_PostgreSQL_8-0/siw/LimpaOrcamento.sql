CREATE OR REPLACE FUNCTION limpaorcamento()
  RETURNS character varying AS
$$
DECLARE
   c_acoes        record;
   c_tarefas      record;
   c_programas    record;
   w_existe       numeric(10);
BEGIN

  for c_tarefas in select * from siw_solicitacao a where a.sq_menu = 1203 loop
     delete from gd_demanda_log_arq a where a.sq_demanda_log in (select b.sq_demanda_log from gd_demanda_log b where b.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao);
     delete from gd_demanda_log     a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from gd_demanda_interes a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from gd_demanda_envolv  a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from gd_demanda         a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from siw_solic_log_arq  a where a.sq_siw_solic_log in (select b.sq_siw_solic_log from siw_solic_log b where b.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao);
     delete from siw_solic_log      a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from siw_solic_arquivo  a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from pj_etapa_demanda   a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
     delete from siw_solicitacao    a where a.sq_siw_solicitacao = c_tarefas.sq_siw_solicitacao;
  end loop;


  for  c_acoes in select * from siw_solicitacao a where a.sq_menu = 1202 loop
     delete from pj_recurso_etapa   a where a.sq_projeto_etapa in (select b.sq_projeto_etapa  from pj_projeto_etapa b where b.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao);
     delete from pj_projeto_etapa   a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from pj_projeto_recurso a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from pj_projeto_log     a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from pj_projeto_interes a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from pj_projeto_envolv  a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from or_acao_prioridade a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from or_acao_financ     a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from or_acao            a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from pj_projeto         a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from siw_solic_log      a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from siw_solic_arquivo  a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
     delete from siw_solicitacao    a where a.sq_siw_solicitacao = c_acoes.sq_siw_solicitacao;
  end loop;


 delete from or_acao_ppa;

  for c_programas in select * from or_acoes order by programa, acao desc loop
     If c_programas.acao is not null Then
        select count(*) into w_existe
          from or_acao_ppa            a
               inner join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
         where trim(a.codigo) = trim(c_programas.acao)
           and trim(b.codigo) = trim(c_programas.programa);

        If w_existe = 0 Then
           insert into or_acao_ppa (sq_acao_ppa, sq_acao_ppa_pai, cliente, codigo, nome, aprovado, empenhado, liquidado)
           (select nextval('sq_acao_ppa'), a.sq_acao_ppa, 362, c_programas.acao,c_programas.descricao, c_programas.autorizado, c_programas.empenhado, c_programas.liquidado
              from or_acao_ppa a
             where a.codigo = c_programas.programa
           );
        Else
           update or_acao_ppa set
              aprovado   = c_programas.autorizado,
              empenhado  = c_programas.empenhado,
              liquidado  = c_programas.liquidado
           where trim(codigo) = trim(c_programas.acao);
        End If;
     Else
        select count(*) into w_existe from or_acao_ppa a where trim(a.codigo) = trim(c_programas.programa);

        If w_existe = 0 Then
           insert into or_acao_ppa (sq_acao_ppa, sq_acao_ppa_pai, cliente, codigo, nome)
           values (NEXTVAL('sq_acao_ppa'), null, 362, c_programas.programa, c_programas.descricao);
        End If;
     End If;
  end loop;

  
  RETURN null;
END; $$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION limpaorcamento() OWNER TO siw;
