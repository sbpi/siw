create or replace procedure SP_GetLcPortalCont
   (p_cliente         in number,
    p_chave           in number   default null,
    p_chave_aux       in number   default null,
    p_sq_lcfinalidade in number   default null,
    p_result          out sys_refcursor) is
    
begin
    -- Recupera os contratos que o usuário pode ver
    open p_result for 
       select a.sq_portal_contrato, a.cliente,         a.sq_unidade,     a.sq_pessoa,
              a.sq_contrato_pai,    a.vigencia_inicio, a.vigencia_fim,   a.assinatura,
              a.publicacao,         a.valor,           a.processo,       a.objeto,
              a.publicar,           a.empenho,         a.observacao,     a.numero,
              a.nome,               a.nome_resumido,   a.cpf,            a.cnpj,
              a.sexo,               a.pessoa_juridica,
              case a.sexo when 'M' then 'Masculino' else 'Feminino' end nm_sexo,
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
              case when e.sq_pessoa  is not null 
                   then e.cpf
                   else case when f.sq_pessoa is not null 
                             then f.cnpj
                             else null 
                        end 
              end cd_pessoa,
              case when e.sq_pessoa  is not null 
                   then 'F'
                   else case when f.sq_pessoa is not null 
                             then 'J'
                             else null 
                        end 
              end tp_pessoa,
              case a.publicar when 'S' then 'Sim' else 'Não' end nm_publicar
         from lc_portal_contrato                 a
              inner      join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
              inner      join lc_portal_lic      c on (a.sq_portal_lic      = c.sq_portal_lic)
              inner      join co_pessoa          d on (a.sq_pessoa           = d.sq_pessoa)
              left outer join co_pessoa_fisica   e on (a.sq_pessoa           = e.sq_pessoa)
              left outer join co_pessoa_juridica f on (a.sq_pessoa           = f.sq_pessoa)
              inner      join lc_finalidade      h on (c.sq_lcfinalidade    = h.sq_lcfinalidade)
              inner      join lc_julgamento      i on (c.sq_lcjulgamento    = i.sq_lcjulgamento)
              inner      join lc_situacao        j on (c.sq_lcsituacao      = j.sq_lcsituacao)
              inner      join lc_fonte_recurso   k on (c.sq_lcfonte_recurso = k.sq_lcfonte_recurso)              
              inner      join lc_modalidade      l on (c.sq_lcmodalidade    = l.sq_lcmodalidade)
              inner      join eo_unidade         m on (c.sq_unidade         = m.sq_unidade)               
         where a.cliente         = p_cliente
           and (p_chave            is null or (p_chave           is not null and a.sq_portal_lic = p_chave))
           and (p_chave_aux        is null or (p_chave_aux       is not null and a.sq_portal_contrato = p_chave_aux))
           and (p_sq_lcfinalidade  is null or (p_sq_lcfinalidade is not null and c.sq_lcfinalidade = p_sq_lcfinalidade))
           ;
end SP_GetLcPortalCont;
/

