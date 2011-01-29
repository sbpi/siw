create or replace FUNCTION SP_PutEOUnidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_tipo_unidade           numeric,
    p_sq_area_atuacao           numeric,
    p_sq_unidade_gestora        numeric,
    p_sq_unidade_pai            numeric,
    p_sq_unidade_pagadora       numeric,
    p_sq_pessoa_endereco        numeric,
    p_ordem                     numeric,
    p_email                     varchar,
    p_codigo                    varchar,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_informal                  varchar,
    p_vinculada                 varchar,
    p_adm_central               varchar,
    p_unidade_gestora           varchar,
    p_unidade_pagadora          varchar,
    p_externo                  varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
     insert into eo_unidade 
            (sq_unidade, sq_tipo_unidade, sq_area_atuacao, sq_unidade_gestora, sq_unidade_pai,
             sq_unid_pagadora, sq_pessoa_endereco, ordem, email, codigo, sq_pessoa, nome, 
             sigla, informal, vinculada, adm_central, unidade_gestora, unidade_pagadora, externo,ativo
            )
     (select Nvl(p_Chave, nextVal('sq_unidade')),
                 p_sq_tipo_unidade,
                 p_sq_area_atuacao,
                 p_sq_unidade_gestora,
                 p_sq_unidade_pai,
                 p_sq_unidade_pagadora,
                 p_sq_pessoa_endereco,
                 p_ordem,
                 p_email,
                 p_codigo,
                 p_cliente,
                 trim(p_nome),
                 trim(p_sigla),
                 p_informal,
                 p_vinculada,
                 p_adm_central,
                 p_unidade_gestora,
                 p_unidade_pagadora,
                 p_externo,
                 p_ativo
           
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_unidade set
         sq_tipo_unidade      = p_sq_tipo_unidade,
         sq_area_atuacao      = p_sq_area_atuacao,
         sq_unidade_gestora   = p_sq_unidade_gestora,
         sq_unidade_pai       = p_sq_unidade_pai,
         sq_unid_pagadora     = p_sq_unidade_pagadora,
         sq_pessoa_endereco   = p_sq_pessoa_endereco,
         ordem                = p_ordem,
         email                = p_email,
         codigo               = p_codigo,
         nome                 = trim(p_nome),
         sigla                = trim(p_sigla),
         informal             = p_informal,
         vinculada            = p_vinculada,
         adm_central          = p_adm_central,
         unidade_gestora      = p_unidade_gestora,
         unidade_pagadora     = p_unidade_pagadora,
         externo              = p_externo,
         ativo                = p_ativo
      where sq_unidade   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_unidade where sq_unidade = p_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;