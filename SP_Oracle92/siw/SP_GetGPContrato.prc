create or replace procedure SP_GetGPContrato
   (p_cliente                 in number,
    p_chave                   in number    default null,
    p_sq_pessoa               in number    default null,
    p_modalidade_contrato     in number    default null,
    p_unidade_lotacao         in number    default null,
    p_filhos_lotacao          in varchar2  default null,
    p_unidade_exercicio       in number    default null,
    p_filhos_exercicio        in varchar2  default null,
    p_afastamento             in number    default null,
    p_dt_ini                  in date      default null,
    p_dt_fim                  in date      default null,
    p_data                    in varchar2  default null,
    p_restricao               in varchar2  default null,
    p_result    out sys_refcursor) is
    
    l_item        varchar2(18);
    l_afastamento varchar2(200) := p_afastamento ||',';
    x_afastamento varchar2(200) := '';
    
begin
   
   If p_afastamento is not null Then
      Loop
         l_item  := Trim(substr(l_afastamento,1,Instr(l_afastamento,',')-1));
         If Length(l_item) > 0 Then
            x_afastamento := x_afastamento||','''||to_number(l_item)||'''';
         End If;
         l_afastamento := substr(l_afastamento,Instr(l_afastamento,',')+1,200);
         Exit when length(l_afastamento)=0;
      End Loop;
      x_afastamento := substr(x_afastamento,2,200);
   End If;
   
   If p_restricao is null Then
      -- Recupera todos ou um colaborador
      open p_result for 
         select e.sq_contrato_colaborador chave, a.sq_pessoa, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero, a.observacoes, 
                b.sq_tipo_vinculo, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.matricula, g.nome nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao, e.trata_username, e.trata_ferias, e.trata_extras, e.entrada_manha, e.saida_manha,
                e.entrada_tarde, e.saida_tarde, e.entrada_noite, e.saida_noite, e.sabado, e.domingo, e.centro_custo,
                minutos2horario(e.minutos_diarios) as carga_diaria, e.minutos_diarios, dados_solic(e.centro_custo) as dados_cc,
                e.banco_horas_saldo, e.banco_horas_data, e.seguro_vida, e.seguro_saude, e.seguro_odonto, e.plano_vida, e.plano_saude, e.plano_odonto, e.vale_refeicao,
                e.observacao_beneficios, e.data_atestado, e.dias_experiencia,
                retornaBancoHoras(e.sq_contrato_colaborador, 
                                  1, 
                                  to_char(e.inicio,'yyyymm'), 
                                  case when to_char(add_months(sysdate,-1),'yyyymm') < to_char(e.inicio,'yyyymm') then to_char(e.inicio,'yyyymm') else to_char(add_months(coalesce(p_dt_fim,sysdate),-1),'yyyymm') end, 
                                  null
                                 ) as banco_horas_mensal,
                h.sigla||' ('||g.nome||')' local, g.ramal, h.nome as nm_unidade_exercicio, h.sigla as sg_unidade_exercicio,
                i.nome nm_modalidade_contrato, j.nome as nm_posto_trabalho, 
                l.nome nm_unidade_lotacao, l.sigla as sg_unidade_lotacao,
                m.sq_menu as sq_menu_cc, m.titulo as nm_cc, m.codigo_interno as cd_cc,
                e.remuneracao_inicial, dados_ferias(p_chave,p_data) as dados_ferias
           from gp_colaborador                          a
                inner    join co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                              a.cliente   = b.sq_pessoa_pai
                                                             )
                inner    join co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner    join gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa)
                  left   join eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    left join eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  left   join gp_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  inner  join eo_posto_trabalho         j on (e.sq_posto_trabalho       = j.sq_posto_trabalho)
                  inner  join eo_unidade                l on (e.sq_unidade_lotacao      = l.sq_unidade)
                  left   join siw_solicitacao           m on (e.centro_custo            = m.sq_siw_solicitacao)
          where a.cliente              = p_cliente 
            and (p_chave               is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_sq_pessoa           is null or (p_sq_pessoa           is not null and a.sq_pessoa               = p_sq_pessoa))
            and (p_modalidade_contrato is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao     is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao   = p_unidade_lotacao) or 
                                                                                          (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade 
                                                                                                                                                         from eo_unidade
                                                                                                                                                       start with sq_unidade = p_unidade_lotacao
                                                                                                                                                       connect by prior sq_unidade = sq_unidade_pai
                                                                                                                                                      )
                                                                                          )
                                                                                         )
                                                  )
                )
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or 
                                                                                          (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade 
                                                                                                                                                           from eo_unidade
                                                                                                                                                         start with sq_unidade = p_unidade_exercicio
                                                                                                                                                         connect by prior sq_unidade = sq_unidade_pai
                                                                                                                                                        )
                                                                                          )
                                                                                         )
                                                  )
                )
           and (p_afastamento          is null or (p_afastamento         is not null and 0 < (select count(sq_tipo_afastamento) from gp_afastamento where sq_contrato_colaborador = e.sq_contrato_colaborador and sq_tipo_afastamento in (x_afastamento))))
           and (p_dt_ini               is null or (p_dt_ini              is not null and 0 < (select count(sq_tipo_afastamento) 
                                                                                                from gp_afastamento 
                                                                                               where sq_contrato_colaborador = e.sq_contrato_colaborador
                                                                                                 and ((inicio_data           between p_dt_ini    and p_dt_fim)   or
                                                                                                      (Nvl(fim_data,sysdate) between p_dt_ini    and p_dt_fim)   or
                                                                                                      (p_dt_ini              between inicio_data and Nvl(fim_data,sysdate)) or
                                                                                                      (p_dt_fim              between inicio_data and Nvl(fim_data,sysdate))
                                                                                                     )
                                                                                             )
                                                   )
                );
   End If;
end SP_GetGPContrato;
/
