create or replace function siw.SF_Consulta(
       p_restricao    varchar,
       p_ctcc         numeric,
       p_sq_pessoa    numeric,
       p_cpf          varchar,
       p_cnpj         varchar,
       p_nome         varchar,
       p_documento    varchar,
       p_inicio       date,
       p_fim          date,
       p_comprovante  varchar,
       p_inicio_nf    date,
       p_fim_nf       date)
       RETURNS refcursor  AS
$BODY$declare
    p_result  refcursor;
begin
  If upper(p_restricao) = 'DOLAR' Then
     open p_result for
        select distinct(to_char(data,'yyyy/mm'))as mes,valor
          from corporativo.gn_moedacotacoes@sicof a
         where data between now()-360 and now()+10
         order by 1 desc;
  Elsif upper(p_restricao) = 'NOME' Then
     open p_result for
        select b.handle, b.cgccpf, b.nome
          from corporativo.gn_pessoas@sicof b
         where upper(b.nome) like '%'||upper(replace(p_nome,'''',''''''))||'%'
        order by seguranca.acentos@sicof(nome);
  Elsif upper(p_restricao) = 'NM_PESSOA' Then
     open p_result for
        select cgccpf, nome from corporativo.gn_pessoas@sicof where (p_sq_pessoa is not null and handle = p_sq_pessoa)
        UNION
        select cgccpf, nome from corporativo.gn_pessoas@sicof where (p_cnpj is not null and cgccpf = p_cnpj)
        UNION
        select cgccpf, nome from corporativo.gn_pessoas@sicof where (p_cpf is not null and cgccpf = p_cpf);
  Elsif upper(p_restricao) = 'NM_PROJETO' Then
     open p_result for select nome from corporativo.ct_cc@sicof where handle = p_ctcc;
  Elsif upper(p_restricao) = 'PROJETOS' Then
     open p_result for select a.HANDLE, a.NOME, a.CODIGOUNESCO, a.INICIO, a.TERMINO from CORPORATIVO.CT_CC@sicof a where a.ultimonivel='S' order by a.nome;
  Elsif upper(p_restricao) = 'CONTRATOS' Then
     open p_result for
        select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio,
               to_char(c.duracaofim,'dd/mm/yyyy') fim, c.duracaofim,
               d.codigounesco projeto,
               decode(c.tipodepagamento,1,'Permanente',2,'Consultor',3,'Produto',4,'Financiamento de atividades')||' ('||
               decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade,
               seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.handle, e.nome, e.cgccpf, c.totcontratacao,
               b.acordo
          from corporativo.un_solicitacaoadministrativa@sicof              a
               inner   join corporativo.un_sol_adm_certifica@sicof         b on (a.handle     = b.numsolicitacao)
                 inner join corporativo.ct_cc@sicof                        d on (b.acordo     = d.handle)
               inner   join corporativo.un_termoreferenciapf@sicof         c on (a.handle     = c.numerosolicitacao)
               inner   join corporativo.gn_pessoas@sicof                   e on (a.contratado = e.handle)
         where (p_sq_pessoa is null or (p_sq_pessoa is not null and e.handle = p_sq_pessoa))
           and (p_ctcc      is null or (p_ctcc      is not null and b.acordo = p_ctcc))
           and (p_cnpj      is null or (p_cnpj      is not null and e.cgccpf = p_cnpj))
           and (p_cpf       is null or (p_cpf       is not null and e.cgccpf = p_cpf))
           and (p_documento is null or (p_documento is not null and a.automatico_sa = p_documento))
           and (p_inicio    is null or (p_inicio    is not null and (duracaoinicio between p_inicio and p_fim or
                                                                     duracaofim    between p_inicio and p_fim or
                                                                     p_inicio      between duracaoinicio and duracaofim or
                                                                     p_fim         between duracaoinicio and duracaofim
                                                                    )
                                       )
               )
        UNION
        select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio,
               to_char(c.duracaofim,'dd/mm/yyyy') fim, c.duracaofim,
               d.codigounesco projeto,
               decode(c.tipodepagamento,1,'Serviços',2,'Aquis.Mat/Bens',3,'Pub/Serv.Gráf.',4,'Promoção Eventos','Financiamento de atividades')||' ('||
               decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade,
               seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.handle, e.nome, e.cgccpf, c.totcontratacao,
               b.acordo
          from corporativo.un_solicitacaoadministrativa@sicof              a
               inner   join corporativo.un_sol_adm_certifica@sicof         b on (a.handle     = b.numsolicitacao)
                 inner join corporativo.ct_cc@sicof                        d on (b.acordo     = d.handle)
               inner   join corporativo.un_termoreferenciapj@sicof         c on (a.handle     = c.solicitacao)
               inner   join corporativo.gn_pessoas@sicof                   e on (a.contratado = e.handle)
         where (p_sq_pessoa is null or (p_sq_pessoa is not null and e.handle = p_sq_pessoa))
           and (p_ctcc      is null or (p_ctcc      is not null and b.acordo = p_ctcc))
           and (p_cnpj      is null or (p_cnpj      is not null and e.cgccpf = p_cnpj))
           and (p_cpf       is null or (p_cpf       is not null and e.cgccpf = p_cpf))
           and (p_documento is null or (p_documento is not null and a.automatico_sa = p_documento))
           and (p_inicio    is null or (p_inicio    is not null and (duracaoinicio between p_inicio and p_fim or
                                                                     duracaofim    between p_inicio and p_fim or
                                                                     p_inicio      between duracaoinicio and duracaofim or
                                                                     p_fim         between duracaoinicio and duracaofim or
                                                                     trunc(a.datainclusao) between p_inicio and p_fim
                                                                    )
                                       )
               )
        order by duracaoinicio desc;
  Elsif upper(p_restricao) = 'PAGAMENTOS' Then
     open p_result for
        select a.handle, a.automatico_sp documento, Decode(c.handle,null,a.proposito_pgto,c.ds_portugues) historico,
               Nvl(to_char(a.dt_vcto,'dd/mm/yyyy'),'-') inicio,
               d.codigounesco projeto,
               (Nvl(a.valornominal,0) - Nvl(a.abatimento,0)) Valor,
               seguranca.fcfaseatual@sicof(a.automatico_sp) fase_atual, b.nome,
               b.handle sq_pessoa, a.acordo, b.cgccpf, a.dt_vcto
          from corporativo.Un_Sol_Pgto@sicof       a,
              corporativo.Gn_Pessoas@sicof         b,
              corporativo.Un_HistoricoPadrao@sicof c,
              corporativo.ct_cc@sicof              d
         where a.Favorecido      = b.Handle
           and a.historicopadrao = c.handle (+)
           and a.acordo          = d.handle
           and (p_sq_pessoa   is null or (p_sq_pessoa   is not null and b.handle = p_sq_pessoa))
           and (p_ctcc        is null or (p_ctcc        is not null and a.acordo = p_ctcc))
           and (p_cnpj        is null or (p_cnpj        is not null and b.cgccpf = p_cnpj))
           and (p_cpf         is null or (p_cpf         is not null and b.cgccpf = p_cpf))
           and (p_documento   is null or (p_documento   is not null and a.automatico_sp = p_documento))
           and (p_inicio      is null or (p_inicio      is not null and (a.dt_vcto between p_inicio and p_fim or trunc(a.datainclusao) between p_inicio and p_fim)))
           and (p_comprovante is null or (p_comprovante is not null and a.handle in (select a.automatico_sp
                                                                                       from corporativo.un_sol_pgto_doc_anexos@sicof a
                                                                                      where a.numerodoc  like '%'||p_comprovante||'%'
                                                                                        and (p_inicio_nf is null or (p_inicio_nf is not null and a.data between p_inicio_nf and p_fim_nf))
                                                                                    )
                                         )
               )
       order by dt_vcto desc;
  Elsif upper(p_restricao) = 'NR_COMPROVANTE' Then
     open p_result for
        select numerodoc
          from corporativo.un_sol_pgto_doc_anexos@sicof a
         where a.automatico_sp = p_comprovante
        order by a.numerodoc;
  Elsif upper(p_restricao) = 'VIAGENS' Then
     open p_result for
        select a.handle, a.automatico_spd documento, a.finalidade historico,
               nvl(to_char(a.dt_inicio,'dd/mm/yyyy'),'-') inicio,
               nvl(to_char(a.dt_fim,'dd/mm/yyyy'),'-') fim,
               d.codigounesco projeto,
               a.valortotal Valor,
               seguranca.fcfaseatual@sicof(a.automatico_spd) fase_atual, b.nome
          from corporativo.Un_SolicitacaoPD@sicof a,
               corporativo.Gn_Pessoas@sicof       b,
               corporativo.ct_cc@sicof            d
         where a.contratado     = b.Handle
           and a.acordo         = d.handle
           and (p_sq_pessoa   is null or (p_sq_pessoa   is not null and b.handle = p_sq_pessoa))
           and (p_ctcc        is null or (p_ctcc        is not null and a.acordo = p_ctcc))
           and (p_cnpj        is null or (p_cnpj        is not null and b.cgccpf = p_cnpj))
           and (p_cpf         is null or (p_cpf         is not null and b.cgccpf = p_cpf))
           and (p_documento   is null or (p_documento   is not null and a.automatico_spd = upper(trim(p_documento))))
           and (p_inicio      is null or (p_inicio      is not null and (a.dt_inicio  between p_inicio and p_fim or
                                                                         a.dt_fim     between p_inicio and p_fim or
                                                                         trunc(a.dt_inclusao) between p_inicio and p_fim
                                                                        )
                                         )
               )
        order by a.dt_inicio desc;
  End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SF_Consulta(
       p_restricao    varchar,
       p_ctcc         numeric,
       p_sq_pessoa    numeric,
       p_cpf          varchar,
       p_cnpj         varchar,
       p_nome         varchar,
       p_documento    varchar,
       p_inicio       date,
       p_fim          date,
       p_comprovante  varchar,
       p_inicio_nf    date,
       p_fim_nf       date) OWNER TO siw;
