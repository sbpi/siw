create or replace procedure sp_getMTBem
   (p_cliente        in number,
    p_usuario        in number,
    p_chave          in number     default null,
    p_ctcc           in number     default null,
    p_projeto        in number     default null,
    p_financeiro     in varchar2   default null,
    p_tipo_material  in number     default null,
    p_material       in varchar2   default null,
    p_rgp            in number     default null,
    p_descricao      in varchar2   default null,
    p_marca          in varchar2   default null,
    p_modelo         in varchar2   default null,
    p_observacao     in varchar2   default null,
    p_ativo          in varchar2   default null,
    p_almoxarifado   in number     default null,
    p_endereco       in number     default null,
    p_unidade        in number     default null,
    p_localizacao    in number     default null,
    p_situacao       in number     default null,
    p_inicio         in date       default null,
    p_fim            in date       default null,
    p_codigo_externo in varchar2   default null,
    p_restricao      in varchar2   default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera bens permanentes
      open p_result for
         select p.sq_permanente as chave, p.cliente, p.sq_localizacao, p.sq_almoxarifado, 
                p.sq_cc, p.sq_material, p.sq_entrada_item, p.sq_mtsituacao, p.sq_projeto,
                p.fornecedor_garantia, p.numero_rgp, p.data_tombamento, p.codigo_externo,
                p.descricao_complementar, p.numero_serie, p.marca, p.modelo, p.data_fim_garantia, 
                p.vida_util, p.observacao, 
                p.cc_patrimonial, p.cc_depreciacao, p.cc_data, p.cc_pessoa, 
                to_char(p.cc_data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_cc_data,
                p.ativo, case p.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                a.nome||case when p.marca is not null then ' '||p.marca end||
                        case when p.modelo is not null then ' '||p.modelo end||
                        case when p.numero_serie is not null then ' Série: '||p.numero_serie end nome_completo,
                a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, 
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome nm_localizacao, 
                e.sq_unidade, e.nome||' ('||e.sigla||')' nm_unidade, e.sigla sg_unidade,
                f.logradouro,
                s.nome nm_situacao,
                x.nome nm_almoxarifado,
                g.nome nm_cc, g.sigla sg_cc,
                h.titulo nm_projeto, h.codigo_interno cd_projeto,
                o.nome cc_pessoa_nome, o.nome_resumido cc_pessoa_nome_res,
                brl.valor_aquisicao vl_aquisicao_brl, brl.valor_atual vl_atual_brl, brl.data_valor_atual dt_vl_atual_brl,
                case when brl.valor_aquisicao is not null then calculaDepreciacao(p.sq_permanente, brl.sq_moeda, null, p_fim) else null end vl_depreciado_brl,
                case when brl.valor_aquisicao is not null then round(brl.valor_atual/(p.vida_util*365) * 30,2) else null end vl_deprec_mensal_brl,
                usd.valor_aquisicao vl_aquisicao_usd, usd.valor_atual vl_atual_usd, usd.data_valor_atual dt_vl_atual_usd,
                case when usd.valor_aquisicao is not null then calculaDepreciacao(p.sq_permanente, usd.sq_moeda, null, p_fim) else null end vl_depreciado_usd,
                case when usd.valor_aquisicao is not null then round(usd.valor_atual/(p.vida_util*12),2) else null end vl_deprec_mensal_usd,
                eur.valor_aquisicao vl_aquisicao_eur, eur.valor_atual vl_atual_eur, eur.data_valor_atual dt_vl_atual_eur,
                case when eur.valor_aquisicao is not null then calculaDepreciacao(p.sq_permanente, eur.sq_moeda, null, p_fim) else null end vl_depreciado_eur,
                case when eur.valor_aquisicao is not null then round(eur.valor_atual/(p.vida_util*12),2) else null end vl_deprec_mensal_eur
           from mt_permanente                            p
                inner           join cl_material         a  on (p.sq_material         = a.sq_material)
                  inner         join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner           join eo_localizacao      d  on (p.sq_localizacao      = d.sq_localizacao)
                  inner         join eo_unidade          e  on (d.sq_unidade          = e.sq_unidade)
                  inner         join co_pessoa_endereco  f  on (d.sq_pessoa_endereco  = f.sq_pessoa_endereco)
                inner           join mt_situacao         s  on (p.sq_mtsituacao       = s.sq_mtsituacao)
                inner           join mt_almoxarifado     x  on (p.sq_almoxarifado     = x.sq_almoxarifado)
                left            join ct_cc               g  on (p.sq_cc               = g.sq_cc)
                left            join siw_solicitacao     h  on (p.sq_projeto          = h.sq_siw_solicitacao)
                left            join mt_entrada_item     i  on (p.sq_entrada_item     = i.sq_entrada_item)
                  left          join fn_documento_item   j  on (i.sq_documento_item   = j.sq_documento_item)
                    left        join fn_lancamento_doc   k  on (j.sq_lancamento_doc   = k.sq_lancamento_doc)
                      left      join fn_lancamento       l  on (k.sq_siw_solicitacao  = l.sq_siw_solicitacao)
                        left    join siw_solicitacao     m  on (l.sq_siw_solicitacao  = m.sq_siw_solicitacao)
                left            join co_pessoa           o on (p.cc_pessoa            = o.sq_pessoa)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'BRL'
                                     )                brl  on (p.sq_permanente       = brl.sq_permanente)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'USD'
                                     )                usd  on (p.sq_permanente       = usd.sq_permanente)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'EUR'
                                     )                eur  on (p.sq_permanente       = eur.sq_permanente)
          where a.cliente         = p_cliente
            and (p_chave          is null or (p_chave          is not null and p.sq_permanente       = p_chave))
            and (p_material       is null or (p_material       is not null and acentos(a.nome)       like '%'||acentos(p_material)||'%'))
            and (p_tipo_material  is null or (p_tipo_material  is not null and a.sq_tipo_material    = p_tipo_material))
            and (p_situacao       is null or (p_situacao       is not null and p.sq_mtsituacao       = p_situacao))
            and (p_localizacao    is null or (p_localizacao    is not null and p.sq_localizacao      = p_localizacao))
            and (p_almoxarifado   is null or (p_almoxarifado   is not null and p.sq_almoxarifado     = p_almoxarifado))
            and (p_endereco       is null or (p_endereco       is not null and d.sq_pessoa_endereco  = p_endereco))
            and (p_unidade        is null or (p_unidade        is not null and e.sq_unidade          = p_unidade))
            and (p_rgp            is null or (p_rgp            is not null and p.numero_rgp          = p_rgp))
            and (p_descricao      is null or (p_descricao      is not null and acentos(p.descricao_complementar) like '%'||acentos(p_descricao)||'%'))
            and (p_codigo_externo is null or (p_codigo_externo is not null and acentos(p.codigo_externo) like '%'||acentos(p_codigo_externo)||'%'))
            and (p_marca          is null or (p_marca          is not null and acentos(p.marca)      like '%'||acentos(p_marca)||'%'))
            and (p_modelo         is null or (p_modelo         is not null and acentos(p.modelo)     like '%'||acentos(p_modelo)||'%'))
            and (p_observacao     is null or (p_observacao     is not null and acentos(p.observacao) like '%'||acentos(p_observacao)||'%'))
            and (p_ctcc           is null or (p_ctcc           is not null and p.sq_cc               = p_ctcc))
            and (p_projeto        is null or (p_projeto        is not null and p.sq_projeto          = p_projeto))
            and (p_financeiro     is null or (p_financeiro     is not null and m.codigo_interno      = p_financeiro))
            and (p_inicio         is null or (p_inicio         is not null and 
                                                               ((p_restricao = 'GARANTIA' and p.data_fim_garantia between p_inicio and p_fim) or
                                                                (nvl(p_restricao,'-') <> 'GARANTIA' and p.data_tombamento  between p_inicio and p_fim)
                                                               )
                                             )
                )
            and (p_ativo          is null or (p_ativo         is not null and p.ativo            = p_ativo));
   ElsIf p_restricao = 'EXISTE' or p_restricao = 'EXISTECOD' Then
      -- Verifica se o nome ou a codigo do material ou serviço já foi inserida
      open p_result for 
         select count(a.sq_material) as existe
           from mt_permanente  a
          where a.cliente        = p_cliente
            and (p_restricao     <> 'EXISTE' or
                 (p_restricao    = 'EXISTE' and sq_permanente  <> coalesce(p_chave,0))
                )
            and (p_rgp           is null or (p_rgp           is not null and a.numero_rgp     = p_rgp));
   End If;
end sp_getMTBem;
/
