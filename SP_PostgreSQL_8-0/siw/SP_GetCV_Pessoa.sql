CREATE OR REPLACE FUNCTION siw.SP_GetCV_Pessoa
   (p_cliente   numeric,
    p_cpf       varchar)
  RETURNS refcursor AS
$BODY$

DECLARE
 
    
    p_result          refcursor;
begin
  -- Recupera os dados de identificação do currículo
  open p_result for
     select a.sq_pessoa, a.nome, a.nome_resumido, c.sq_siw_arquivo,
            b.nascimento, b.rg_numero, b.rg_emissor, b.rg_emissao, b.cpf, b.sq_cidade_nasc,
            b.passaporte_numero, b.sq_pais_passaporte, b.sq_etnia, b.sq_deficiencia, b.sexo, b.sq_formacao,
            case b.sexo when 'M' then 'Masculino' else 'Feminino' end as nm_sexo,
            c.sq_estado_civil, c.inclusao, c.alteracao,
            d.residencia_outro_pais, d.mudanca_nacionalidade, d.mudanca_nacionalidade_medida,
            d.emprego_seis_meses, d.impedimento_viagem_aerea, d.objecao_informacoes, d.prisao_envolv_justica,
            d.motivo_prisao, d.fato_relevante_vida, d.servidor_publico, d.servico_publico_inicio, d.servico_publico_fim,
            d.atividades_civicas, d.familiar,
            case d.residencia_outro_pais when 'S' then 'Sim' else 'Não' end as nm_residencia,
            case d.mudanca_nacionalidade when 'S' then 'Sim' else 'Não' end as nm_mudanca,
            case d.emprego_seis_meses when 'S' then 'Sim' else 'Não' end as nm_emprego,
            case d.impedimento_viagem_aerea when 'S' then 'Sim' else 'Não' end as nm_impedimento,
            case d.objecao_informacoes when 'S' then 'Sim' else 'Não' end as nm_objecao,
            case d.prisao_envolv_justica when 'S' then 'Sim' else 'Não' end as nm_prisao,
            case d.servidor_publico when 'S' then 'Sim' else 'Não' end as nm_servidor,
            case d.familiar when 'S' then 'Sim' else 'Não' end as nm_familiar,
            e.nome as nm_cidade_nascimento, e.sq_pais as pais, e.co_uf as uf,
            f.nome as nm_estado_civil,
            g.nome as nm_pais_passaporte,
            h.nome as nm_pais_nascimento,
            i.nome as nm_uf_nascimento,
            j.nome as nm_etnia,
            k.nome as nm_deficiencia,
            l.nome as nm_formacao
       from siw.co_pessoa                          a
            inner        join siw.co_pessoa_fisica b on (a.sq_pessoa          = b.sq_pessoa)
              inner      join siw.co_cidade        e on (b.sq_cidade_nasc     = e.sq_cidade)
                inner    join siw.co_pais          h on (e.sq_pais            = h.sq_pais)
                inner    join siw.co_uf            i on (e.co_uf              = i.co_uf)
              left outer join siw.co_pais          g on (b.sq_pais_passaporte = g.sq_pais)
              left outer join siw.co_etnia         j on (b.sq_etnia           = j.sq_etnia)
              left outer join siw.co_deficiencia   k on (b.sq_deficiencia     = k.sq_deficiencia)
              inner      join siw.co_formacao      l on (b.sq_formacao        = l.sq_formacao)
            inner        join siw.cv_pessoa        c on (a.sq_pessoa          = c.sq_pessoa)
              left outer join siw.cv_pessoa_hist   d on (c.sq_pessoa          = d.sq_pessoa)
              inner      join siw.co_estado_civil  f on (c.sq_estado_civil    = f.sq_estado_civil)
      where a.sq_pessoa_pai = p_cliente
        and b.cpf           = p_cpf;
end 
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCV_Pessoa
   (p_cliente   numeric,
    p_cpf       varchar) OWNER TO siw;
