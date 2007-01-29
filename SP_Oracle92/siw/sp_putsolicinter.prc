create or replace procedure SP_PutSolicInter
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_sq_pessoa           in number,
    p_sq_tipo_interessado in number
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de interessados
      insert into siw_solicitacao_interessado
         (sq_solicitacao_interessado,         sq_siw_solicitacao, sq_pessoa,   sq_tipo_interessado)
      values
         (sq_solicitacao_interessado.nextval, p_chave,            p_sq_pessoa, p_sq_tipo_interessado);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de interessados da solicitação
      update siw_solicitacao_interessado set
          sq_tipo_interessado = p_sq_tipo_interessado
      where sq_siw_solicitacao = p_chave
        and sq_pessoa          = p_sq_pessoa;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      delete siw_solicitacao_interessado
       where sq_siw_solicitacao = p_chave
         and sq_pessoa          = p_sq_pessoa;
   End If;
end SP_PutSolicInter;
/
