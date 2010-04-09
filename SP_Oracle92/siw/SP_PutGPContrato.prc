create or replace procedure SP_PutGPContrato
   (p_operacao                 in  varchar2              ,
    p_cliente                  in  number    default null,
    p_chave                    in  number    default null,    
    p_cc                       in  number    default null,    
    p_sq_pessoa                in  number    default null,
    p_sq_posto_trabalho        in  number    default null,
    p_sq_modalidade_contrato   in  number    default null,
    p_sq_unidade_lotacao       in  number    default null,    
    p_sq_unidade_exercicio     in  number    default null,
    p_sq_localizacao           in  number    default null,
    p_matricula                in  varchar2  default null,
    p_inicio                   in  date      default null,
    p_fim                      in  date      default null,
    p_trata_username           in  varchar2  default null,
    p_trata_ferias             in  varchar2  default null,
    p_trata_extras             in  varchar2  default null,        
    p_tipo_vinculo             in  number    default null,
    p_entrada_manha            in  varchar2  default null,
    p_saida_manha              in  varchar2  default null,
    p_entrada_tarde            in  varchar2  default null,
    p_saida_tarde              in  varchar2  default null,    
    p_entrada_noite            in  varchar2  default null,
    p_saida_noite              in  varchar2  default null,
    p_sabado                   in  varchar2  default null,
    p_domingo                  in  varchar2  default null,
    p_banco_horas_saldo        in  varchar2  default null,             
    p_banco_horas_data         in  date      default null,
    p_remuneracao_inicial      in  number    default null,
    p_seguro_saude             in  varchar2,
    p_seguro_odonto            in  varchar2,
    p_seguro_vida              in  varchar2,        
    p_plano_saude              in  varchar2  default null,
    p_plano_odonto             in  varchar2  default null,
    p_plano_vida               in  varchar2  default null,
    p_vale_refeicao            in  varchar2,
    p_vale_transporte          in  varchar2,
    p_observacao_beneficios    in  varchar2  default null,
    p_data_atestado            in  date,
    p_dias_experiencia         in  number
   ) is
   
   w_colaborador      number(18);
   
begin
   If p_chave is null or p_operacao = 'I' Then
      -- Insere registro
      -- Verifica se o colaborador já existe
      select count(*) into w_colaborador 
        from gp_colaborador 
       where sq_pessoa = p_sq_pessoa 
         and cliente = p_cliente;
      If w_colaborador = 0 Then   
         insert into gp_colaborador (sq_pessoa, cliente) 
         values (p_sq_pessoa, p_cliente);
      End If;
      insert into gp_contrato_colaborador 
             (sq_contrato_colaborador,         cliente,                sq_pessoa,              centro_custo, 
              sq_posto_trabalho,               sq_localizacao,         sq_unidade_lotacao,     sq_unidade_exercicio,  
              sq_modalidade_contrato,          matricula,              inicio,                 fim, 
              trata_username,                  trata_ferias,           trata_extras,           entrada_manha, 
              saida_manha,                     entrada_tarde,          saida_tarde,            entrada_noite, 
              saida_noite,                     sabado,                 domingo,
              banco_horas_saldo,               banco_horas_data,       remuneracao_inicial,
              seguro_saude,                    seguro_odonto,          seguro_vida,
              plano_saude,                     plano_odonto,           plano_vida,
              vale_refeicao,                   vale_transporte,        observacao_beneficios, data_atestado,
              dias_experiencia         
              )
      (select sq_contrato_colaborador.nextval, p_cliente,              p_sq_pessoa,           p_cc,
              p_sq_posto_trabalho,             p_sq_localizacao,       p_sq_unidade_lotacao,  p_sq_unidade_exercicio, 
              p_sq_modalidade_contrato,        p_matricula,            p_inicio,              p_fim, 
              p_trata_username,                p_trata_ferias,         p_trata_extras,        p_entrada_manha, 
              p_saida_manha,                   p_entrada_tarde,        p_saida_tarde,         p_entrada_noite, 
              p_saida_noite,                   p_sabado,               p_domingo,
              p_banco_horas_saldo,             p_banco_horas_data,     p_remuneracao_inicial,
              p_seguro_saude,                  p_seguro_odonto,        p_seguro_vida,
              p_plano_saude,                   p_plano_odonto,         p_plano_vida,
              p_vale_refeicao,                 p_vale_transporte,      p_observacao_beneficios, p_data_atestado,
              p_dias_experiencia
         from dual);
       If p_fim is null Then
          update co_pessoa 
             set sq_tipo_vinculo = p_tipo_vinculo,
                 funcionario     = 'S'
           where sq_pessoa = p_sq_pessoa;  
       End If;
   Elsif p_chave is not null and p_operacao = 'A' Then
      -- Altera registro
      update gp_contrato_colaborador
         set centro_custo           = p_cc,
             sq_posto_trabalho      = p_sq_posto_trabalho,
             sq_localizacao         = p_sq_localizacao,
             sq_unidade_lotacao     = p_sq_unidade_lotacao,
             sq_unidade_exercicio   = p_sq_unidade_exercicio,             
             sq_modalidade_contrato = p_sq_modalidade_contrato, 
             matricula              = p_matricula,
             inicio                 = p_inicio,
             fim                    = p_fim,
             trata_username         = p_trata_username,
             trata_ferias           = p_trata_ferias,
             trata_extras           = p_trata_extras,
             entrada_manha          = p_entrada_manha,
             saida_manha            = p_saida_manha,
             entrada_tarde          = p_entrada_tarde,
             saida_tarde            = p_saida_tarde,
             entrada_noite          = p_entrada_noite,
             saida_noite            = p_saida_noite,
             sabado                 = p_sabado,
             domingo                = p_domingo,
             banco_horas_saldo      = p_banco_horas_saldo,
             banco_horas_data       = p_banco_horas_data,
             remuneracao_inicial    = p_remuneracao_inicial,
             seguro_saude           = p_seguro_saude,
             seguro_odonto          = p_seguro_odonto,
             seguro_vida            = p_seguro_vida,                          
             plano_saude            = p_plano_saude,
             plano_odonto           = p_plano_odonto,
             plano_vida             = p_plano_vida,
             vale_refeicao          = p_vale_refeicao,
             observacao_beneficios  = p_observacao_beneficios,
             data_atestado          = p_data_atestado,
             dias_experiencia       = p_dias_experiencia
       where sq_contrato_colaborador = p_chave;

      update co_pessoa 
         set sq_tipo_vinculo = p_tipo_vinculo,
             funcionario     = 'S'
       where sq_pessoa = p_sq_pessoa;  
   Elsif p_operacao = 'E' Then
      -- Encerra um contrato
      update gp_contrato_colaborador
         set fim = p_fim
       where sq_contrato_colaborador = p_chave;
      update co_pessoa 
         set funcionario     = 'N'
       where sq_pessoa = p_sq_pessoa;  
      update sg_autenticacao
         set ativo = 'N'
       where sq_pessoa = p_sq_pessoa;
       
   End If;
end SP_PutGPContrato;
/
