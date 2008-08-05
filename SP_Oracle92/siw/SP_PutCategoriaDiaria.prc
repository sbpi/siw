create or replace procedure SP_PutCategoriaDiaria
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_categoria_diaria ( sq_categoria_diaria,         cliente,   nome,         ativo            )
      (select                           sq_categoria_diaria.nextval, p_cliente, trim(p_nome), p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_categoria_diaria set
         nome                   = trim(p_nome),
         ativo                  = p_ativo
      where sq_categoria_diaria = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_categoria_diaria where sq_categoria_diaria = p_chave;
   End If;
end SP_PutCategoriaDiaria;
/
