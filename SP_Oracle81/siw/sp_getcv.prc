create or replace procedure SP_GetCV
   (p_cliente   in number,
    p_chave     in number    default null,
    p_sigla     in varchar2  default null,
    p_tipo      in varchar2  default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_sigla='CVIDENT' or p_sigla='CVHIST' Then
      -- Recupera os dados de identificação do currículo
      open p_result for
         select a.sq_pessoa, a.nome, a.nome_resumido,
                b.nascimento, b.rg_numero, b.rg_emissor, b.rg_emissao, Nvl(b.cpf,n.username) cpf, b.sq_cidade_nasc,
                b.passaporte_numero, b.sq_pais_passaporte, b.sq_etnia, b.sq_deficiencia, b.sexo, b.sq_formacao,
                decode(b.sexo,'M','Masculino','Feminino') nm_sexo,
                c.sq_estado_civil, c.inclusao, c.alteracao,
                d.residencia_outro_pais, d.mudanca_nacionalidade, d.mudanca_nacionalidade_medida,
                d.emprego_seis_meses, d.impedimento_viagem_aerea, d.objecao_informacoes, d.prisao_envolv_justica,
                d.motivo_prisao, d.fato_relevante_vida, d.servidor_publico, d.servico_publico_inicio, d.servico_publico_fim,
                d.atividades_civicas, d.familiar,
                decode(d.residencia_outro_pais   , 'S', 'Sim', 'Não') nm_residencia,
                decode(d.mudanca_nacionalidade   , 'S', 'Sim', 'Não') nm_mudanca,
                decode(d.emprego_seis_meses      , 'S', 'Sim', 'Não') nm_emprego,
                decode(d.impedimento_viagem_aerea, 'S', 'Sim', 'Não') nm_impedimento,
                decode(d.objecao_informacoes     , 'S', 'Sim', 'Não') nm_objecao,
                decode(d.prisao_envolv_justica   , 'S', 'Sim', 'Não') nm_prisao,
                decode(d.servidor_publico        , 'S', 'Sim', 'Não') nm_servidor,
                decode(d.familiar                , 'S', 'Sim', 'Não') nm_familiar,
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
           from co_pessoa        a,
                co_pessoa_fisica b,
                sg_autenticacao  n,
                co_cidade        e,
                co_pais          h,
                co_uf            i,
                co_pais          g,
                co_etnia         j,
                co_deficiencia   k,
                co_formacao      l,
                cv_pessoa        c,
                cv_pessoa_hist   d,
                co_estado_civil  f,
                siw_arquivo      m
          where (a.sq_pessoa          = b.sq_pessoa (+))
            and (a.sq_pessoa          = n.sq_pessoa (+))
            and (b.sq_cidade_nasc     = e.sq_cidade (+))
            and (e.sq_pais            = h.sq_pais (+))
            and (e.co_uf              = i.co_uf (+))
            and (b.sq_pais_passaporte = g.sq_pais        (+))
            and (b.sq_etnia           = j.sq_etnia       (+))
            and (b.sq_deficiencia     = k.sq_deficiencia (+))
            and (b.sq_formacao        = l.sq_formacao (+))
            and (a.sq_pessoa          = c.sq_pessoa      (+))
            and (c.sq_pessoa          = d.sq_pessoa      (+))
            and (c.sq_estado_civil    = f.sq_estado_civil (+))
            and (c.sq_siw_arquivo     = m.sq_siw_arquivo (+))
            and a.sq_pessoa_pai       = p_cliente
            and ((p_chave             is null) or (p_chave is not null and a.sq_pessoa     = p_chave));
   End If;
end SP_GetCV;
/
