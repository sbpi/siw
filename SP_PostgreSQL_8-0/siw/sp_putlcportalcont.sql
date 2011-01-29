create or replace FUNCTION SP_PutLcPortalCont
   (p_operacao             varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_numero              varchar,
    p_objeto              varchar,
    p_processo            varchar,
    p_empenho             varchar,
    p_assinatura          date,
    p_vigencia_i          date,
    p_vigencia_f          date,
    p_publicacao          date,
    p_valor               numeric,
    p_pessoa_juridica     varchar,
    p_cnpj                varchar,
    p_cpf                 varchar,
    p_nome                varchar,
    p_nome_resumido       varchar,
    p_sexo                varchar,
    p_sq_pessoa           numeric,
    p_unidade             numeric,    
    p_observacao          varchar,
    p_publicar            varchar  
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_portal_contrato') into w_Chave;
       
      -- Insere registro em LC_PORTAL_CONTRATO
      insert into lc_portal_contrato
        (sq_portal_contrato,  cliente,     sq_unidade,  sq_pessoa,    vigencia_inicio,   
         vigencia_fim,        assinatura,  publicacao,  valor,        processo, 
         objeto,              publicar,    empenho,     observacao,   numero,
         pessoa_juridica,     cnpj,        cpf,         nome,         nome_resumido,
         sexo,                sq_portal_lic
        )
      values
        (w_chave,           p_cliente,        p_unidade,       p_sq_pessoa,          p_vigencia_i, 
         p_vigencia_f,      p_assinatura,     p_publicacao,    p_valor,              p_processo, 
         p_objeto,          p_publicar,       p_empenho,       p_observacao,         p_numero,
         p_pessoa_juridica, p_cnpj,           p_cpf,           p_nome,               p_nome_resumido,
         p_sexo,            p_chave
        );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de contratos
      update lc_portal_contrato set 
         sq_unidade         = p_unidade,
         sq_pessoa          = p_sq_pessoa,
         vigencia_inicio    = p_vigencia_i,
         vigencia_fim       = p_vigencia_f,
         assinatura         = p_assinatura,
         publicacao         = p_publicacao,
         valor              = p_valor,
         processo           = p_processo,
         objeto             = p_objeto,
         publicar           = p_publicar,
         empenho            = p_empenho,
         observacao         = p_observacao,
         numero             = p_numero,
         pessoa_juridica    = p_pessoa_juridica,
         cnpj               = p_cnpj,
         cpf                = p_cpf,
         nome               = p_nome,
         nome_resumido      = p_nome_resumido,
         sexo               = p_sexo
       where sq_portal_contrato = p_Chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove os registros vinculados à ao contrato
      DELETE FROM lc_portal_contrato_item where sq_portal_contrato = p_chave_aux;
      DELETE FROM lc_portal_contrato      where sq_portal_contrato = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;