create or replace procedure sp_PutAssunto_PA
   (p_operacao         in  varchar2             ,
    p_chave            in  number   default null,
    p_cliente          in  number   default null,
    p_chave_pai        in  number   default null,
    p_codigo           in  varchar2 default null,
    p_descricao        in  varchar2 default null,
    p_detalhamento     in  varchar2 default null,
    p_observacao       in  varchar2 default null,
    p_corrente_guarda  in  number   default null,
    p_corrente_anos    in  number   default null,
    p_intermed_guarda  in  number   default null,
    p_intermed_anos    in  number   default null,
    p_final_guarda     in  number   default null,
    p_final_anos       in  number   default null,
    p_destinacao_final in  number   default null,
    p_ativo            in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_assunto (sq_assunto, cliente, sq_assunto_pai, codigo, descricao, detalhamento, observacao, fase_corrente_guarda,
                              fase_corrente_anos, fase_intermed_guarda, fase_intermed_anos, fase_final_guarda,
                              fase_final_anos, destinacao_final, ativo)
      (select sq_assunto.nextval, p_cliente, p_chave_pai, p_codigo, p_descricao, p_detalhamento, p_observacao, p_corrente_guarda, 
              p_corrente_anos, p_intermed_guarda, p_intermed_anos, p_final_guarda, p_final_anos, 
              p_destinacao_final, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_assunto
         set sq_assunto_pai       = p_chave_pai,
             codigo               = p_codigo,
             descricao            = p_descricao,
             detalhamento         = p_detalhamento,
             observacao           = p_observacao,
             fase_corrente_guarda = p_corrente_guarda,
             fase_corrente_anos   = p_corrente_anos,
             fase_intermed_guarda = p_intermed_guarda,
             fase_intermed_anos   = p_intermed_anos,
             fase_final_guarda    = p_final_guarda,
             fase_final_anos      = p_final_anos,
             destinacao_final     = p_destinacao_final,
             ativo                = p_ativo
       where sq_assunto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_assunto
       where sq_assunto = p_chave;
   End If;
end sp_PutAssunto_PA;
/
