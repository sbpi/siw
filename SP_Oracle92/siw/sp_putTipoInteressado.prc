create or replace procedure sp_putTipoInteressado
   (p_operacao   in  varchar2            ,
    p_menu    in  varchar2 default null,
    p_chave      in  number   default null,
    p_nome       in  varchar2 default null,
    p_ordem      in  varchar2 default null,
    p_sigla      in  varchar2 default null,
    p_descricao  in  varchar2 default null,
    p_ativo      in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_tipo_interessado
        (sq_tipo_interessado,         sq_menu,   nome,   ordem,   sigla,          descricao,   ativo)
      values
        (sq_tipo_interessado.nextval, p_menu, p_nome, p_ordem, upper(p_sigla), p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_tipo_interessado
         set nome          = p_nome,
             ordem         = p_ordem,
             sigla         = upper(p_sigla),
             descricao     = p_descricao,
             ativo         = p_ativo
       where sq_tipo_interessado = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_tipo_interessado where sq_tipo_interessado = p_chave;
   End If;
end sp_putTipoInteressado;
/
