create or replace function SP_PutDMSegVinc
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_segmento              numeric,
    p_sq_tipo_pessoa           numeric,
    p_nome                     varchar,
    p_padrao                   varchar,
    p_ativo                    varchar,
    p_interno                  varchar,
    p_contratado               varchar,
    p_ordem                    numeric
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dm_seg_vinculo 
         (sq_seg_vinculo, sq_segmento, sq_tipo_pessoa, nome,  padrao, 
          ativo,               interno,     contratado,     ordem
         )
       (select nextval('sq_segmento_vinculo'),
               p_sq_segmento,
               p_sq_tipo_pessoa,
               trim(p_nome),
               p_padrao,
               p_ativo,
               p_interno,
               p_contratado,
               p_ordem
       );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dm_seg_vinculo set
         sq_tipo_pessoa  = p_sq_tipo_pessoa,
         nome            = trim(p_nome),
         padrao          = p_padrao,
         ativo           = p_ativo,
         interno         = p_interno,
         contratado      = p_contratado,
         ordem           = p_ordem
      where sq_seg_vinculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete from dm_seg_vinculo where sq_seg_vinculo = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;