CREATE OR REPLACE FUNCTION siw.SP_GetGPColaborador
   (p_cliente                 numeric,
    p_chave                   numeric,
    p_nome                    varchar,
    p_ativo                   varchar,
    p_modalidade_contrato     numeric,
    p_unidade_lotacao         numeric,
    p_filhos_lotacao          varchar,
    p_unidade_exercicio       numeric,
    p_filhos_exercicio        varchar,
    p_afastamento             varchar,
    p_dt_ini                  date,
    p_dt_fim                  date,
    p_ferias                  varchar,
    p_viagem                  varchar,
    p_chave_aux               numeric,
    p_restricao               varchar)
       RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
   l_item        varchar(18);
    l_afastamento varchar(200) := replace(p_afastamento,' ','') ||',';
    x_afastamento varchar(200) := '';

begin

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
         select a.sq_pessoa as chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.sq_tipo_vinculo, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_modalidade_contrato, e.sq_contrato_colaborador, d.email, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                f.nome as localizacao, f.fax, f.telefone, f.ramal, f.telefone2,
                g.nome as unidade, g.sigla, g.email as email_unidade,
                h.logradouro as endereco, (i.nome||'-'||i.co_uf) as Cidade, i.ddd
           from siw.gp_colaborador                                a
                  inner          join siw.co_pessoa               b on (a.sq_pessoa      = b.sq_pessoa)
                    left outer   join siw.co_pessoa_fisica        c on (a.sq_pessoa      = c.sq_pessoa)
                    left outer   join (select w.sq_pessoa, w.logradouro as email
                                         from siw.co_pessoa_endereco            w
                                              inner   join siw.co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                              inner   join siw.co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                                inner join siw.co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                        where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                          and x.email              = 'S'
                                          and x.ativo              = 'S'
                                          and w.padrao             = 'S'
                                      )                       d on (b.sq_pessoa      = d.sq_pessoa)
                    left outer   join (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro
                                         from siw.co_pessoa_endereco          w
                                              inner join siw.co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                              inner join siw.co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                              inner join siw.co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                        where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                          and x.nome               = 'Comercial'
                                          and x.ativo              = 'S'
                                          and w.padrao             = 'S'
                                      )                       h on (b.sq_pessoa_pai = h.sq_pessoa)
                  left outer     join siw.co_cidade               i on (h.sq_cidade     = i.sq_cidade)
                  inner          join siw.gp_contrato_colaborador e on (a.sq_pessoa      = e.sq_pessoa and
                                                                    e.fim            is null)
                    left outer   join siw.eo_localizacao          f on (e.sq_localizacao = f.sq_localizacao)
                      left outer join siw.eo_unidade              g on (f.sq_unidade     = g.sq_unidade)
          where a.cliente  = p_cliente
            and ((p_chave  is null) or (p_chave is not null and a.sq_pessoa    = p_chave))
            and ((p_nome   is null) or (p_nome  is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')))
            and (p_ativo       is null or (p_ativo    = 'S' and e.fim is null));
   ElsIf p_restricao = 'AFASTAMENTO' Then
      -- Recupera os colaboradores que estão ligados a um afastamento
      open p_result for
         select distinct a.sq_pessoa as chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador,
                h.sigla||' ('||g.nome||' - R.'||g.ramal||')' as slocal
           from siw.gp_colaborador                          a
                inner      join siw.co_pessoa               b on (a.sq_pessoa = b.sq_pessoa and
                                                              a.cliente   = b.sq_pessoa_pai)
                inner      join siw.co_pessoa_fisica        c on (a.sq_pessoa = c.sq_pessoa)
                inner      join siw.gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                              e.fim is null)
                    inner   join siw.eo_localizacao         g on (e.sq_localizacao         = g.sq_localizacao)
                      inner join siw.eo_unidade             h on (g.sq_unidade             = h.sq_unidade)
                  inner    join siw.gp_afastamento          f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
          where a.cliente      = p_cliente
            and (p_chave       is null or (p_chave    is not null and a.sq_pessoa     = p_chave))
            and (p_nome        is null or (p_nome     is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')));
   Elsif p_restricao = 'SELAFAST' Then
      -- Recupera todas ou um colaborador
      open p_result for
         select d.sq_pessoa as chave, d.nome, d.nome_resumido, d.nome_resumido_ind, c.sq_contrato_colaborador,
                g.sigla||' ('||f.nome||' - R.'||f.ramal||')' as slocal
           from siw.gp_tipo_afastamento                        a
                inner       join siw.gp_afastamento_modalidade b on (a.sq_tipo_afastamento    = b.sq_tipo_afastamento)
                  inner     join siw.gp_contrato_colaborador   c on (b.sq_modalidade_contrato = c.sq_modalidade_contrato and
                                                                 c.fim is null)
                    inner   join siw.eo_localizacao            f on (c.sq_localizacao         = f.sq_localizacao)
                      inner join siw.eo_unidade                g on (f.sq_unidade             = g.sq_unidade)
                    inner   join siw.co_pessoa                 d on (c.sq_pessoa              = d.sq_pessoa)
                      inner join siw.co_pessoa_fisica          e on (d.sq_pessoa              = e.sq_pessoa and
                                                                 e.sexo                   = case a.sexo when 'A' then e.sexo else a.sexo end
                                                                )
          where a.cliente             = p_cliente
            and a.sq_tipo_afastamento = p_chave_aux
            and (p_nome               is null or (p_nome     is not null and (d.nome_indice like '%'||acentos(p_nome)||'%' or d.nome_resumido_ind like '%'||acentos(p_nome)||'%')));
   Elsif p_restricao = 'COLABORADOR' Then
      -- Recupera os colaboradores ativos
      open p_result for
         select distinct a.sq_pessoa as chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla as nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla||' ('||g.nome||')' as slocal, g.ramal,
                i.nome as nm_modalidade_contrato, c.cpf
           from siw.gp_colaborador                                a
                inner          join siw.co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join siw.co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join siw.gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join siw.eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join siw.eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  inner        join siw.gp_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
          where a.cliente              = p_cliente
            and p_afastamento          is null
            and p_viagem               is null
            and (p_chave               is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_nome                is null or (p_nome                is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')))
            and (p_modalidade_contrato is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao     is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao   = p_unidade_lotacao)   or (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_lotacao
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_exercicio
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
     UNION
         select distinct a.sq_pessoa as chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla as nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade as_lotacao, e.sq_unidade as as_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla||' ('||g.nome||')' as slocal, g.ramal,
                i.nome as nm_modalidade_contrato, c.cpf
           from siw.gp_colaborador                                a
                inner          join siw.co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join siw.co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join siw.gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join siw.eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join siw.eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  inner        join siw.gp_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  inner        join siw.gp_afastamento            f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
          where a.cliente              = p_cliente
            and p_afastamento          is not null
            and (p_chave               is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_nome                is null or (p_nome                is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')))
            and (p_modalidade_contrato is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao     is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao   = p_unidade_lotacao)   or (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_lotacao
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_exercicio
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
           and instr(x_afastamento,''''||f.sq_tipo_afastamento||'''') > 0
           and (p_dt_ini               is null or (p_dt_ini              is not null and (f.inicio_data between p_dt_ini and p_dt_fim) or (f.fim_data between p_dt_ini and p_dt_fim)))
     UNION
         select distinct a.sq_pessoa as chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla as nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla||' ('||g.nome||')' as slocal, g.ramal,
                i.nome as nm_modalidade_contrato, c.cpf
           from gp_colaborador                                a
                inner          join siw.co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join siw.co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join siw.gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join siw.eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join siw.eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  inner        join siw.gp_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  inner        join siw.pd_missao                 j on (a.sq_pessoa               = j.sq_pessoa)
                    inner      join siw.siw_solicitacao           l on (j.sq_siw_solicitacao      = l.sq_siw_solicitacao)
          where a.cliente              = p_cliente
            and p_viagem               is not null
            and (p_chave               is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_nome                is null or (p_nome                is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')))
            and (p_modalidade_contrato is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao     is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao   = p_unidade_lotacao)   or (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_lotacao
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_exercicio
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
           and ((l.inicio              between p_dt_ini and p_dt_fim) or (l.fim      between p_dt_ini and p_dt_fim));
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetGPColaborador
   (p_cliente                 numeric,
    p_chave                   numeric,
    p_nome                    varchar,
    p_ativo                   varchar,
    p_modalidade_contrato     numeric,
    p_unidade_lotacao         numeric,
    p_filhos_lotacao          varchar,
    p_unidade_exercicio       numeric,
    p_filhos_exercicio        varchar,
    p_afastamento             varchar,
    p_dt_ini                  date,
    p_dt_fim                  date,
    p_ferias                  varchar,
    p_viagem                  varchar,
    p_chave_aux               numeric,
    p_restricao               varchar) OWNER TO siw;

