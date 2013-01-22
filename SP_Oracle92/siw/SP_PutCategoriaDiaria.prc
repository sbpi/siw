create or replace procedure SP_PutCategoriaDiaria
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_sigla                    in  varchar2 default null,
    p_ativo                    in  varchar2 default null,
    p_tramite_especial         in  varchar2 default null,
    p_dias_prest_contas        in  number   default null,
    p_valor_complemento        in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_categoria_diaria ( sq_categoria_diaria,         cliente,   nome,   sigla,   ativo,   tramite_especial,   dias_prestacao_contas, valor_complemento)
      (select                           sq_categoria_diaria.nextval, p_cliente, p_nome, p_sigla, p_ativo, p_tramite_especial, p_dias_prest_contas,   p_valor_complemento from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_categoria_diaria set
         nome                   = p_nome,
         sigla                  = p_sigla,
         ativo                  = p_ativo,
         tramite_especial       = p_tramite_especial,
         dias_prestacao_contas  = p_dias_prest_contas,
         valor_complemento      = p_valor_complemento
      where sq_categoria_diaria = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_categoria_diaria where sq_categoria_diaria = p_chave;
   End If;
end SP_PutCategoriaDiaria;
/
