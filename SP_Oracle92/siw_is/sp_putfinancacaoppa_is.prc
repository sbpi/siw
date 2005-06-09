create or replace procedure SP_PutFinancAcaoPPA_IS
   (p_operacao                 in  varchar2,
    p_chave                    in  number  ,
    p_programa                 in  varchar2,
    p_acao                     in  varchar2,
    p_subacao                  in  varchar2,
    p_cliente                  in  number,
    p_ano                      in  number,
    p_observacao               in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere os registro
      insert into is_acao_financ(sq_siw_solicitacao, cd_programa, cd_acao, cd_subacao, cliente, ano, observacao) 
      values (p_chave, p_programa, p_acao, p_subacao, p_cliente, p_ano, p_observacao);
   Elsif p_operacao = 'A' Then
      -- Altera os resgitros
      update is_acao_financ set
        observacao  = p_observacao
      where sq_siw_solicitacao = p_chave 
        and cd_programa = p_programa
        and cd_acao     = p_acao
        and cd_subacao  = p_subacao
        and cliente     = p_cliente
        and ano         = p_ano;
   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      delete is_acao_financ 
       where sq_siw_solicitacao = p_chave 
         and cd_programa        = p_programa
         and cd_acao            = p_acao
         and cd_subacao         = p_subacao
         and cliente            = p_cliente
         and ano                = p_ano;
   End If;
end SP_PutFinancAcaoPPA_IS;
/

