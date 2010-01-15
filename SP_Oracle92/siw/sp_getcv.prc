create or replace procedure SP_GetCV
   (p_cliente   in number,
    p_chave     in number    default null,
    p_sigla     in varchar2  default null,
    p_tipo      in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If Substr(p_sigla,1,2) = 'CV' Then
      -- Recupera os dados de identificação do currículo
      open p_result for 
         select a.sq_pessoa, a.nome, a.nome_resumido,
                b.nascimento, b.rg_numero, b.rg_emissor, b.rg_emissao, coalesce(b.cpf, n.username) as cpf , b.sq_cidade_nasc,
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
                e.nome as nm_cidade_nascimento, e.sq_pais as pais, e.co_uf uf,
                f.nome as nm_estado_civil,
                g.nome as nm_pais_passaporte,
                h.nome as nm_pais_nascimento,
                i.nome as nm_uf_nascimento,
                j.nome as nm_etnia,
                k.nome as nm_deficiencia,
                l.nome as nm_formacao,
                m.sq_siw_arquivo, m.nome as nm_foto, m.descricao as ds_foto, m.inclusao as dt_foto,
                m.tamanho as tm_foto, m.tipo as tp_foto, m.caminho as ln_foto
           from co_pessoa                      a
                left     join sg_autenticacao  n on (a.sq_pessoa          = n.sq_pessoa)
                left     join co_pessoa_fisica b on (a.sq_pessoa          = b.sq_pessoa)
                  left   join co_cidade        e on (b.sq_cidade_nasc     = e.sq_cidade)
                    left join co_pais          h on (e.sq_pais            = h.sq_pais)
                    left join co_uf            i on (e.co_uf              = i.co_uf)
                  left   join co_pais          g on (b.sq_pais_passaporte = g.sq_pais)
                  left   join co_etnia         j on (b.sq_etnia           = j.sq_etnia)
                  left   join co_deficiencia   k on (b.sq_deficiencia     = k.sq_deficiencia)
                  left   join co_formacao      l on (b.sq_formacao        = l.sq_formacao)
                left     join cv_pessoa        c on (a.sq_pessoa          = c.sq_pessoa)
                  left   join cv_pessoa_hist   d on (c.sq_pessoa          = d.sq_pessoa)
                  left   join co_estado_civil  f on (c.sq_estado_civil    = f.sq_estado_civil)
                  left   join siw_arquivo      m on (c.sq_siw_arquivo     = m.sq_siw_arquivo)
          where a.sq_pessoa_pai = p_cliente
            and ((p_chave       is null) or (p_chave is not null and a.sq_pessoa     = p_chave));
   End If;
end SP_GetCV;
/
