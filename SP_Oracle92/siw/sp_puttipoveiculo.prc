create or replace procedure SP_PutTipoVeiculo
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_chave_aux                in  number    default null,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_descricao                in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_tipo_veiculo 
        (sq_tipo_veiculo, cliente, sq_grupo_veiculo, nome, sigla, descricao, ativo)
      values
        (sq_tipo_veiculo.nextval, p_cliente, p_chave_aux, p_nome, p_sigla, p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_tipo_veiculo
         set cliente          = p_cliente,
             nome             = p_nome,
             sq_grupo_veiculo = p_chave_aux,             
             sigla            = p_sigla,
             descricao        = p_descricao,             
             ativo = p_ativo
       where sq_tipo_veiculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete sr_tipo_veiculo where sq_tipo_veiculo = p_chave;
   End If;
end SP_PutTipoVeiculo;
/
