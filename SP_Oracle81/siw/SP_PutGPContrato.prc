create or replace procedure SP_PutGPContrato
   (p_operacao                 in  varchar2              ,
    p_cliente                  in  number    default null,
    p_chave                    in  number    default null,    
    p_sq_pessoa                in  number    default null,
    p_sq_posto_trabalho        in  number    default null,
    p_sq_modalidade_contrato   in  number    default null,
    p_sq_unidade_lotacao       in  number    default null,    
    p_sq_unidade_exercicio     in  number    default null,
    p_sq_localizacao           in  number    default null,
    p_matricula                in  varchar2  default null,
    p_inicio                   in  date      default null,
    p_fim                      in  date      default null,
    p_tipo_vinculo             in  number    default null
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
      (sq_contrato_colaborador, cliente, sq_pessoa, sq_posto_trabalho, sq_localizacao, sq_unidade_lotacao,
       sq_unidade_exercicio, sq_modalidade_contrato, matricula, inicio, fim)
      (select sq_contrato_colaborador.nextval, p_cliente, p_sq_pessoa,  p_sq_posto_trabalho, p_sq_localizacao, 
       p_sq_unidade_lotacao, p_sq_unidade_exercicio, p_sq_modalidade_contrato, p_matricula, p_inicio, p_fim from dual);
       If p_fim is null Then
          update co_pessoa 
             set sq_tipo_vinculo = p_tipo_vinculo,
                 funcionario     = 'S'
           where sq_pessoa = p_sq_pessoa;  
       End If;
   Elsif p_chave is not null and p_operacao = 'A' Then
      -- Altera registro
      update gp_contrato_colaborador
         set sq_posto_trabalho      = p_sq_posto_trabalho,
             sq_localizacao         = p_sq_localizacao,
             sq_unidade_lotacao     = p_sq_unidade_lotacao,
             sq_unidade_exercicio   = p_sq_unidade_exercicio,             
             sq_modalidade_contrato = p_sq_modalidade_contrato, 
             matricula              = p_matricula,
             inicio                 = p_inicio,
             fim                    = p_fim
       where sq_contrato_colaborador = p_chave;
   Elsif p_operacao = 'E' Then
      -- Encerra um contrato
      update gp_contrato_colaborador
         set fim = p_fim
       where sq_contrato_colaborador = p_chave;
      update co_pessoa 
         set sq_tipo_vinculo = null,
             funcionario     = 'N'
       where sq_pessoa = p_sq_pessoa;  
      update sg_autenticacao
         set ativo = 'N'
       where sq_pessoa = p_sq_pessoa;
       
   End If;
end SP_PutGPContrato;
/
