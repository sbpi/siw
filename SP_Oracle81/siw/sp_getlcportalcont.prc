create or replace procedure SP_GetLcPortalCont
   (p_cliente         in number,
    p_chave           in number   default null,
    p_chave_aux       in number   default null,
    p_sq_lcfinalidade in number   default null,
    p_result          out siw.sys_refcursor) is

begin
    -- Recupera os contratos que o usuário pode ver
    open p_result for
       select a.sq_portal_contrato, a.cliente,         a.sq_unidade,     a.sq_pessoa,
              a.sq_contrato_pai,    a.vigencia_inicio, a.vigencia_fim,   a.assinatura,
              a.publicacao,         a.valor,           a.processo,       a.objeto,
              a.publicar,           a.empenho,         a.observacao,     a.numero,
              a.nome,               a.nome_resumido,   a.cpf,            a.cnpj,
              a.sexo,               a.pessoa_juridica,
              decode(a.sexo,'M','Masculino','Feminino') nm_sexo,
              b.nome nm_unid,       b.sigla sg_unid,   b.email mail_unid,
              c.sq_portal_lic chave, c.sq_lcfinalidade,  c.edital, c.objeto objetolic, c.abertura,
              c.publicar pub_lic, c.fundamentacao,
              d.nome nm_pessoa,     d.nome_resumido nm_resumido_pessoa, d.nome_indice nm_indice_pessoa,
              e.sexo sx_pessoa,
              h.nome nm_finalidade,
              i.nome nm_criterio,
              j.nome nm_situacao,
              k.nome nm_fonte,
              l.nome nm_modalidade, l.sigla sg_modalidade,
              m.nome nm_unid_lic, m.sigla sg_unid_lic,
              decode(e.sq_pessoa,null,decode(f.sq_pessoa,null,null,f.cnpj),e.cpf) cd_pessoa,
              decode(e.sq_pessoa,null,decode(f.sq_pessoa,null,null,'J'),'F') tp_pessoa,
              decode(a.publicar,'S','Sim','Não') nm_publicar
         from lc_portal_contrato                 a,
              eo_unidade         b,
              lc_portal_lic      c,
              co_pessoa          d,
              co_pessoa_fisica   e,
              co_pessoa_juridica f,
              lc_finalidade      h,
              lc_julgamento      i,
              lc_situacao        j,
              lc_fonte_recurso   k,
              lc_modalidade      l,
              eo_unidade         m
         where (a.sq_unidade         = b.sq_unidade)
           and (a.sq_portal_lic      = c.sq_portal_lic)
           and (a.sq_pessoa           = d.sq_pessoa)
           and (a.sq_pessoa           = e.sq_pessoa (+))
           and (a.sq_pessoa           = f.sq_pessoa (+))
           and (c.sq_lcfinalidade    = h.sq_lcfinalidade)
           and (c.sq_lcjulgamento    = i.sq_lcjulgamento)
           and (c.sq_lcsituacao      = j.sq_lcsituacao)
           and (c.sq_lcfonte_recurso = k.sq_lcfonte_recurso)
           and (c.sq_lcmodalidade    = l.sq_lcmodalidade)
           and (c.sq_unidade         = m.sq_unidade)
           and a.cliente         = p_cliente
           and (p_chave            is null or (p_chave           is not null and a.sq_portal_lic = p_chave))
           and (p_chave_aux        is null or (p_chave_aux       is not null and a.sq_portal_contrato = p_chave_aux))
           and (p_sq_lcfinalidade  is null or (p_sq_lcfinalidade is not null and c.sq_lcfinalidade = p_sq_lcfinalidade))
           ;
end SP_GetLcPortalCont;
/

