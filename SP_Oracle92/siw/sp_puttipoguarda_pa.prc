create or replace procedure sp_PutTipoGuarda_PA
   (p_operacao         in  varchar2             ,
    p_chave            in  number   default null,
    p_cliente          in  number   default null,
    p_sigla            in  varchar2 default null,
    p_descricao        in  varchar2 default null,
    p_fase_corrente    in  varchar2 default null,
    p_fase_intermed    in  varchar2 default null,
    p_fase_final       in  varchar2 default null,
    p_destinacao_final in  varchar2 default null,
    p_ativo            in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_tipo_guarda (sq_tipo_guarda, cliente, sigla, descricao, fase_corrente, 
                                  fase_intermed, fase_final, destinacao_final, ativo)
      (select sq_tipo_guarda.nextval, p_cliente, upper(p_sigla), p_descricao, p_fase_corrente, 
              p_fase_intermed, p_fase_final, p_destinacao_final, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_tipo_guarda
         set cliente          = p_cliente,
             sigla            = upper(p_sigla),
             descricao        = p_descricao,
             fase_corrente    = p_fase_corrente,
             fase_intermed    = p_fase_intermed,
             fase_final       = p_fase_final,
             destinacao_final = p_destinacao_final,
             ativo            = p_ativo
       where sq_tipo_guarda = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_tipo_guarda
       where sq_tipo_guarda = p_chave;
   End If;
end sp_PutTipoGuarda_PA;
/
