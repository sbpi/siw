create or replace FUNCTION SP_PutLcPortalLic
   (p_operacao             varchar,
    p_cliente             numeric,
    p_chave                numeric,
    p_copia                numeric,
    p_objeto              varchar,
    p_edital              varchar,
    p_processo            varchar,
    p_empenho             varchar,
    p_abertura            date,
    p_fundamentacao       varchar,
    p_observacao          varchar,
    p_modalidade          numeric,
    p_fonte               numeric,
    p_finalidade          numeric,
    p_criterio            numeric,
    p_situacao            numeric,
    p_unidade             numeric,
    p_publicar            varchar,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_portal_lic.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into lc_portal_lic
        (sq_portal_lic,   cliente,          sq_unidade,      sq_lcmodalidade,        
         sq_lcfinalidade, sq_lcjulgamento,  sq_lcsituacao,   sq_lcfonte_recurso,   abertura, 
         objeto,          processo,         empenho,         publicar,             observacao,
         edital,          fundamentacao
        )
      values
        (w_chave,         p_cliente,        p_unidade,       p_modalidade,         
         p_finalidade,    p_criterio,       p_situacao,      p_fonte,              p_abertura, 
         p_objeto,        p_processo,       p_empenho,       p_publicar,           p_observacao,
         p_edital,        p_fundamentacao
        );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      update lc_portal_lic set 
         sq_unidade         = p_unidade,
         sq_lcmodalidade    = p_modalidade,
         sq_lcfinalidade    = p_finalidade,
         sq_lcjulgamento    = p_criterio,
         sq_lcsituacao      = p_situacao,
         sq_lcfonte_recurso = p_fonte,
         abertura           = p_abertura,
         fundamentacao      = p_fundamentacao,
         objeto             = p_objeto,
         processo           = p_processo,
         empenho            = p_empenho,
         publicar           = p_publicar,
         observacao         = p_observacao,
         edital             = p_edital
       where sq_portal_lic = p_Chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove os registros vinculados à licitação
      DELETE FROM lc_arquivo              where sq_portal_lic = p_chave;
      DELETE FROM siw_arquivo             where sq_siw_arquivo     in (select sq_siw_arquivo from lc_arquivo where sq_portal_lic = p_chave);
      DELETE FROM lc_portal_contrato_item where sq_portal_lic_item in (select sq_portal_lic_item from lc_portal_lic_item a where sq_portal_lic = p_chave);
      DELETE FROM lc_portal_contrato      where sq_portal_lic = p_chave;
      DELETE FROM lc_portal_lic_item      where sq_portal_lic = p_chave;
      DELETE FROM lc_portal_lic           where sq_portal_lic = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;