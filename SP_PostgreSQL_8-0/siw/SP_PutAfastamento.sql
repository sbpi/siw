create or replace FUNCTION SP_PutAfastamento
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_sq_tipo_afastamento       numeric,
    p_sq_contrato_colaborador   numeric,
    p_inicio_data               date,
    p_inicio_periodo            varchar,
    p_fim_data                  date,
    p_fim_periodo               varchar,
    p_dias                      numeric,
    p_observacao                varchar  
   ) RETURNS VOID AS $$
DECLARE
    c_dados CURSOR FOR
       select e.sq_contrato_colaborador as chave
         from gp_colaborador                     a
              inner join gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                       e.fim       is null)
        where a.cliente  = p_cliente;
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      If p_sq_contrato_colaborador = 0 Then
         For crec in c_dados loop
           -- Insere registro
           insert into gp_afastamento
             (sq_afastamento, cliente, sq_tipo_afastamento, sq_contrato_colaborador, inicio_data, inicio_periodo, 
              fim_data, fim_periodo, dias, observacao)
           values
             (sq_afastamento.nextval, p_cliente,  p_sq_tipo_afastamento, crec.chave, p_inicio_data, p_inicio_periodo, 
              p_fim_data, p_fim_periodo, p_dias, p_observacao);        
         End loop;
      Else
         -- Insere registro
         insert into gp_afastamento
           (sq_afastamento, cliente, sq_tipo_afastamento, sq_contrato_colaborador, inicio_data, inicio_periodo, 
            fim_data, fim_periodo, dias, observacao)
         values
           (sq_afastamento.nextval, p_cliente,  p_sq_tipo_afastamento, p_sq_contrato_colaborador, p_inicio_data, p_inicio_periodo, 
            p_fim_data, p_fim_periodo, p_dias, p_observacao);
      End If;
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_afastamento
         set  sq_tipo_afastamento     = p_sq_tipo_afastamento,
             sq_contrato_colaborador = p_sq_contrato_colaborador,
             inicio_data             = p_inicio_data,
             inicio_periodo          = p_inicio_periodo,
             fim_data                = p_fim_data,
             fim_periodo             = p_fim_periodo,
             dias                    = p_dias,
             observacao              = p_observacao
       where sq_afastamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui os registros de GP_Afastamento_envio
      DELETE FROM gp_afastamento_envio where sq_afastamento = p_chave;
      -- Exclui registro
      DELETE FROM gp_afastamento where sq_afastamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;