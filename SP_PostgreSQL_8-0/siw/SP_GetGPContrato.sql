CREATE OR REPLACE FUNCTION SP_GetGPContrato
   (p_cliente                 numeric,
    p_chave                   numeric,
    p_sq_pessoa               numeric,
    p_modalidade_contrato     numeric,
    p_unidade_lotacao         numeric,
    p_filhos_lotacao          varchar,
    p_unidade_exercicio       numeric,
    p_filhos_exercicio        varchar,
    p_afastamento             numeric,
    p_dt_ini                  date,
    p_dt_fim                  date,
    p_chave_aux               numeric,
    p_restricao               varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
    l_item        varchar(18);
    l_afastamento varchar(200) := p_afastamento ||',';
    x_afastamento varchar(200) := '';
BEGIN
   If p_afastamento is not null Then
      Loop
         l_item  := Trim(substr(l_afastamento,1,Instr(l_afastamento,',')-1));
         If Length(l_item) > 0 Then
            x_afastamento := x_afastamento||','''||to_number(l_item)||'''';
         End If;
         l_afastamento := substr(l_afastamento,Instr(l_afastamento,',')+1,200);
         Exit when l_afastamento is null;
      End Loop;
      x_afastamento := substr(x_afastamento,2,200);
   End If;

   If p_restricao is null Then
      -- Recupera todos ou um colaborador
      open p_result for
         select e.sq_contrato_colaborador as chave, a.sq_pessoa, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero, a.observacoes,
                b.sq_tipo_vinculo, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.matricula, g.nome as nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla||' ('||g.nome||')' as slocal, g.ramal, h.nome as nm_unidade_exercicio, h.sigla as sg_unidade_exercicio,
                i.nome as nm_modalidade_contrato, j.nome as nm_posto_trabalho,
                l.nome as nm_unidade_lotacao, l.sigla as sg_unidade_lotacao
           from siw.gp_colaborador                                a
                inner          join siw.co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join siw.co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join siw.gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa)
                  left outer   join siw.eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    left outer join siw.eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  left outer   join siw.gp_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  left outer   join siw.gp_afastamento            f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
                  inner        join siw.eo_posto_trabalho         j on (e.sq_posto_trabalho       = j.sq_posto_trabalho)
                  inner        join siw.eo_unidade                l on (e.sq_unidade_lotacao      = l.sq_unidade)
          where a.cliente              = p_cliente
            and (p_chave               is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_sq_pessoa           is null or (p_sq_pessoa           is not null and a.sq_pessoa               = p_sq_pessoa))
            and (p_modalidade_contrato is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao     is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao = p_unidade_lotacao) or 
                                                                                          (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade
                                                                                                                                                         from connectby('eo_unidade','sq_unidade','sq_unidade_pai',to_char(p_unidade_lotacao),0) 
                                                                                                                                                              as (sq_unidade numeric, sq_unidade_pai numeric, level int)
                                                                                                                                                      )
                                                                                          )
                                                                                         )
                                                  )
                )
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or
                                                                                          (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade
                                                                                                                                                           from connectby('eo_unidade','sq_unidade','sq_unidade_pai',to_char(p_unidade_exercicio),0) 
                                                                                                                                                                as (sq_unidade numeric, sq_unidade_pai numeric, level int)
                                                                                                                                                        )
                                                                                          )
                                                                                         )
                                                  )
                )
           and (p_afastamento          is null or (p_afastamento         is not null and ''''||to_char(f.sq_tipo_afastamento)||'''' in (x_afastamento)))
           and (p_dt_ini               is null or (p_dt_ini              is not null and ((f.inicio_data           between p_dt_ini      and p_dt_fim)   or
                                                                                          (coalesce(f.fim_data,now()) between p_dt_ini      and p_dt_fim)   or
                                                                                          (p_dt_ini                between f.inicio_data and coalesce(f.fim_data,now())) or
                                                                                          (p_dt_fim                between f.inicio_data and coalesce(f.fim_data,now()))
                                                                                         )
                                                   )
                );
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;
