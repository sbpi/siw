create or replace procedure SP_PutAfastamento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number,
    p_sq_tipo_afastamento      in  number,
    p_sq_contrato_colaborador  in  number,
    p_inicio_data              in  date,
    p_inicio_periodo           in  varchar2,
    p_fim_data                 in  date      default null,
    p_fim_periodo              in  varchar2  default null,
    p_dias                     in  number    default null,
    p_observacao               in  varchar2  default null
   ) is
   cursor c_dados is
       select e.sq_contrato_colaborador as chave
         from gp_colaborador                     a
              inner join gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                       e.fim       is null)
        where a.cliente  = p_cliente;
begin
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
      delete gp_afastamento_envio where sq_afastamento = p_chave;
      -- Exclui registro
      delete gp_afastamento where sq_afastamento = p_chave;
   End If;
end SP_PutAfastamento;
/
