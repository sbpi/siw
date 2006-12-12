create or replace procedure SP_PutGrupoVeiculo
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_descricao                in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_grupo_veiculo 
        (sq_grupo_veiculo, cliente, nome, sigla, descricao, ativo)
      values
        (sq_grupo_veiculo.nextval, p_cliente, p_nome, p_sigla, p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_grupo_veiculo
         set cliente       = p_cliente,
             nome          = p_nome,
             sigla         = p_sigla,
             descricao     = p_descricao,             
             ativo = p_ativo
       where sq_grupo_veiculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete sr_grupo_veiculo where sq_grupo_veiculo = p_chave;
   End If;
end SP_PutGrupoVeiculo;
/
