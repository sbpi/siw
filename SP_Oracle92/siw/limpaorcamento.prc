create or replace procedure LimpaOrcamento is
  cursor c_acoes     is select * from siw_solicitacao a where a.sq_menu = 1202; --1163;
  cursor c_tarefas   is select * from siw_solicitacao a where a.sq_menu = 1203; --1164;
  cursor c_programas is select * from or_acoes order by programa, acao desc;
  w_existe number(18);
begin
  for crec in c_tarefas loop
     delete gd_demanda_log_arq a where a.sq_demanda_log in (select b.sq_demanda_log from gd_demanda_log b where b.sq_siw_solicitacao = crec.sq_siw_solicitacao);
     delete gd_demanda_log     a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete gd_demanda_interes a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete gd_demanda_envolv  a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete gd_demanda         a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solic_log_arq  a where a.sq_siw_solic_log in (select b.sq_siw_solic_log from siw_solic_log b where b.sq_siw_solicitacao = crec.sq_siw_solicitacao);
     delete siw_solic_log      a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solic_arquivo  a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_etapa_demanda   a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solicitacao    a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
  end loop;

  for crec in c_acoes loop
     delete pj_recurso_etapa   a where a.sq_projeto_etapa in (select b.sq_projeto_etapa from pj_projeto_etapa b where b.sq_siw_solicitacao = crec.sq_siw_solicitacao);
     delete pj_projeto_etapa   a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_projeto_recurso a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_projeto_log     a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_projeto_interes a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_projeto_envolv  a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete or_acao_prioridade a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete or_acao_financ     a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete or_acao            a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete pj_projeto         a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solic_log      a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solic_arquivo  a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     delete siw_solicitacao    a where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
  end loop;

  delete or_acao_ppa;

  for crec in c_programas loop
     If crec.acao is not null Then
        select count(*) into w_existe
          from or_acao_ppa            a
               inner join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
         where upper(trim(a.codigo)) = upper(trim(crec.acao))
           and upper(trim(b.codigo)) = upper(trim(crec.programa));

        If w_existe = 0 Then
           insert into or_acao_ppa (sq_acao_ppa, sq_acao_ppa_pai, cliente, codigo, nome, aprovado, empenhado, liquidado)
           (select sq_acao_ppa.nextval, a.sq_acao_ppa, 362, crec.acao, crec.descricao, crec.autorizado, crec.empenhado, crec.liquidado
              from or_acao_ppa a
             where a.codigo = crec.programa
           );
        Else
           update or_acao_ppa set
              aprovado   = crec.autorizado,
              empenhado  = crec.empenhado,
              liquidado  = crec.liquidado
           where upper(trim(codigo)) = upper(trim(crec.acao));
        End If;
     Else
        select count(*) into w_existe from or_acao_ppa a where upper(trim(a.codigo)) = upper(trim(crec.programa));

        If w_existe = 0 Then
           insert into or_acao_ppa (sq_acao_ppa, sq_acao_ppa_pai, cliente, codigo, nome)
           values (sq_acao_ppa.nextval, null, 362, crec.programa, crec.descricao);
        End If;
     End If;
  end loop;

end LimpaOrcamento;
/

