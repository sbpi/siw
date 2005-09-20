create or replace procedure SP_PutGPModalidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_descricao                in  varchar2,
    p_sigla                    in  varchar2,
    p_ferias                   in  varchar2  default null,
    p_username                 in  varchar2  default null,
    p_passagem                 in  varchar2,
    p_diaria                   in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_modalidade_contrato
        (sq_modalidade_contrato, cliente, nome, descricao, sigla, ferias, username, passagem, diaria, ativo)
      values
        (sq_modalidade_contrato.nextval, p_cliente, trim(p_nome), p_descricao, upper(trim(p_sigla)), p_ferias, p_username, p_passagem, p_diaria, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_modalidade_contrato
         set cliente       = p_cliente,
             nome          = trim(p_nome),
             descricao     = p_descricao,
             sigla         = upper(trim(p_sigla)),
             ferias        = p_ferias,
             username      = p_username,
             passagem      = p_passagem,
             diaria        = p_diaria,
             ativo = p_ativo
       where sq_modalidade_contrato = p_chave;
      If p_ativo = 'N' Then
         delete gp_afastamento_modalidade where sq_modalidade_contrato = p_chave;
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui os registro de ligação com os tipos de afastamento
      delete gp_afastamento_modalidade where sq_modalidade_contrato = p_chave;
      -- Exclui registro
      delete gp_modalidade_contrato where sq_modalidade_contrato = p_chave;
   End If;
end SP_PutGPModalidade;
/
