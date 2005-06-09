create or replace procedure SP_GetCV
   (p_cliente   in number,
    p_chave     in number    default null,
    p_sigla     in varchar2  default null,
    p_tipo      in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_sigla='CVIDENT' or p_sigla='CVHIST' Then
      -- Recupera os dados de identificação do currículo
      open p_result for 
         select a.sq_pessoa, a.nome, a.nome_resumido,
                b.nascimento, b.rg_numero, b.rg_emissor, b.rg_emissao, b.cpf, b.sq_cidade_nasc,
                b.passaporte_numero, b.sq_pais_passaporte, b.sq_etnia, b.sq_deficiencia, b.sexo, b.sq_formacao,
                case b.sexo when 'M' then 'Masculino' else 'Feminino' end nm_sexo,
                c.sq_estado_civil, c.inclusao, c.alteracao,
                d.residencia_outro_pais, d.mudanca_nacionalidade, d.mudanca_nacionalidade_medida, 
                d.emprego_seis_meses, d.impedimento_viagem_aerea, d.objecao_informacoes, d.prisao_envolv_justica, 
                d.motivo_prisao, d.fato_relevante_vida, d.servidor_publico, d.servico_publico_inicio, d.servico_publico_fim, 
                d.atividades_civicas, d.familiar,
                case d.residencia_outro_pais when 'S' then 'Sim' else 'Não' end nm_residencia,
                case d.mudanca_nacionalidade when 'S' then 'Sim' else 'Não' end nm_mudanca,
                case d.emprego_seis_meses when 'S' then 'Sim' else 'Não' end nm_emprego,
                case d.impedimento_viagem_aerea when 'S' then 'Sim' else 'Não' end nm_impedimento,
                case d.objecao_informacoes when 'S' then 'Sim' else 'Não' end nm_objecao,
                case d.prisao_envolv_justica when 'S' then 'Sim' else 'Não' end nm_prisao,
                case d.servidor_publico when 'S' then 'Sim' else 'Não' end nm_servidor,
                case d.familiar when 'S' then 'Sim' else 'Não' end nm_familiar,
                e.nome nm_cidade_nascimento, e.sq_pais pais, e.co_uf uf,
                f.nome nm_estado_civil,
                g.nome nm_pais_passaporte,
                h.nome nm_pais_nascimento,
                i.nome nm_uf_nascimento,
                j.nome nm_etnia,
                k.nome nm_deficiencia,
                l.nome nm_formacao,
                m.sq_siw_arquivo, m.nome nm_foto, m.descricao ds_foto, m.inclusao dt_foto,
                m.tamanho tm_foto, m.tipo tp_foto, m.caminho ln_foto
           from co_pessoa                          a
                inner        join co_pessoa_fisica b on (a.sq_pessoa          = b.sq_pessoa)
                  inner      join co_cidade        e on (b.sq_cidade_nasc     = e.sq_cidade)
                    inner    join co_pais          h on (e.sq_pais            = h.sq_pais)
                    inner    join co_uf            i on (e.co_uf              = i.co_uf)
                  left outer join co_pais          g on (b.sq_pais_passaporte = g.sq_pais)
                  left outer join co_etnia         j on (b.sq_etnia           = j.sq_etnia)
                  left outer join co_deficiencia   k on (b.sq_deficiencia     = k.sq_deficiencia)
                  inner      join co_formacao      l on (b.sq_formacao        = l.sq_formacao)
                inner        join cv_pessoa        c on (a.sq_pessoa          = c.sq_pessoa)
                  left outer join cv_pessoa_hist   d on (c.sq_pessoa          = d.sq_pessoa)
                  inner      join co_estado_civil  f on (c.sq_estado_civil    = f.sq_estado_civil)
                  left outer join siw_arquivo      m on (c.sq_siw_arquivo     = m.sq_siw_arquivo)
          where a.sq_pessoa_pai = p_cliente
            and ((p_chave       is null) or (p_chave is not null and a.sq_pessoa     = p_chave));
   End If;
end SP_GetCV;
/

