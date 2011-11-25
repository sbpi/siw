create or replace procedure SP_PutGPTipoAfast
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_limite_dias              in  number,
    p_sexo                     in  varchar2,
    p_percentual_pagamento     in  number,
    p_contagem_dias            in  varchar2,
    p_periodo                  in  varchar2,
    p_sobrepoe_ferias          in  varchar2,
    p_abate_banco_horas        in  varchar2,
    p_abate_ferias             in  varchar2,
    p_falta                    in  varchar2,
    p_ativo                    in  varchar2,
    p_fase                     in varchar2 default null
   ) is
   
   l_item       varchar2(18);
   l_fase       varchar2(200) := p_fase ||',';
   w_chave      number(18);
   
begin
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      -- Recupera a chave
      select sq_tipo_afastamento.nextval into w_chave from dual;
      -- Insere registro
      insert into gp_tipo_afastamento
        (sq_tipo_afastamento, cliente, nome, sigla, limite_dias, sexo, percentual_pagamento, 
         contagem_dias, periodo, abate_banco_horas, abate_ferias, falta_nao_justificada , sobrepoe_ferias, ativo)
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
         Exit when length(l_fase)=0;
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
       
      delete gp_afastamento_modalidade where sq_tipo_afastamento = p_chave;
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
            Exit when length(l_fase)=0;
         End Loop;
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui os registro de ligação com a modalidade
      delete gp_afastamento_modalidade where sq_tipo_afastamento = p_chave;
      -- Exclui registro
      delete gp_tipo_afastamento where sq_tipo_afastamento = p_chave;
   End If;
end SP_PutGPTipoAfast;
/
