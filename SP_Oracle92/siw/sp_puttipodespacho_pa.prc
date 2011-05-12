create or replace procedure sp_PutTipoDespacho_PA
   (p_operacao  in  varchar2             ,
    p_chave     in  number   default null,
    p_cliente   in  number   default null,
    p_nome      in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_descricao in  varchar2 default null,
    p_original  in  varchar2 default null,
    p_ativo     in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_tipo_despacho
             (sq_tipo_despacho,         cliente,   nome,   sigla,          descricao,   despacho_original, ativo)
      (select sq_tipo_despacho.nextval, p_cliente, p_nome, upper(p_sigla), p_descricao, p_original,        p_ativo
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_tipo_despacho
         set cliente           = p_cliente,
             nome              = p_nome,
             sigla             = upper(p_sigla),
             descricao         = p_descricao,
             despacho_original = p_original,
             ativo             = p_ativo
       where sq_tipo_despacho  = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_tipo_despacho where sq_tipo_despacho = p_chave;
   End If;
end sp_PutTipoDespacho_PA;
/
