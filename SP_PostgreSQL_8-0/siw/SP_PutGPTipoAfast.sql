create or replace FUNCTION SP_PutGPTipoAfast
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_limite_dias               numeric,
    p_sexo                      varchar,
    p_percentual_pagamento      numeric,
    p_contagem_dias             varchar,
    p_periodo                   varchar,
    p_sobrepoe_ferias           varchar,
    p_abate_banco_horas         varchar,
    p_abate_ferias              varchar,
    p_falta                     varchar,
    p_ativo                     varchar,
    p_fase                     varchar 
   ) RETURNS VOID AS $$
DECLARE
   
   l_item       varchar(18);
   l_fase       varchar(200) := p_fase ||',';
   w_chave      numeric(18);
   
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      -- Recupera a chave
      select nextVal('sq_tipo_afastamento') into w_chave;
      -- Insere registro
      insert into gp_tipo_afastamento
        (sq_tipo_afastamento, cliente, nome, sigla, limite_dias, sexo, percentual_pagamento, 
         contagem_dias, periodo, abate_banco_horas, abate_ferias, falta_nao_justificada, sobrepoe_ferias, ativo)
      values
        (w_chave, p_cliente, trim(p_nome), upper(trim(p_sigla)), p_limite_dias, p_sexo, p_percentual_pagamento, 
         p_contagem_dias, p_periodo, p_abate_banco_horas, p_abate_ferias, p_falta, p_sobrepoe_ferias, p_ativo);
      If p_fase is not null Then
      
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
           insert into gp_afastamento_modalidade
             (sq_tipo_afastamento, sq_modalidade_contrato)
           values
             (w_chave, l_item);
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null;
      End Loop;
   End If;
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_tipo_afastamento
         set cliente               = p_cliente,
             nome                  = trim(p_nome),
             sigla                 = upper(trim(p_sigla)),
             limite_dias           = p_limite_dias,
             sexo                  = p_sexo,
             percentual_pagamento  = p_percentual_pagamento,
             contagem_dias         = p_contagem_dias,
             periodo               = p_periodo,
             sobrepoe_ferias       = p_sobrepoe_ferias,
             abate_banco_horas     = p_abate_banco_horas,
             abate_ferias          = p_abate_ferias,             
             falta_nao_justificada = p_falta,
             ativo                 = p_ativo
       where sq_tipo_afastamento = p_chave;
       
      DELETE FROM gp_afastamento_modalidade where sq_tipo_afastamento = p_chave;
      If p_fase is not null Then
         Loop
            l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
            If Length(l_item) > 0 Then
               insert into gp_afastamento_modalidade
                 (sq_tipo_afastamento, sq_modalidade_contrato)
               values
                 (p_chave, l_item);
            End If;
            l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
            Exit when l_fase is null;
         End Loop;
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui os registro de ligação com a modalidade
      DELETE FROM gp_afastamento_modalidade where sq_tipo_afastamento = p_chave;
      -- Exclui registro
      DELETE FROM gp_tipo_afastamento where sq_tipo_afastamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;