create or replace procedure SP_PutValorDiaria
   (p_operacao                 in varchar2,
    p_cliente                  in number   default null,
    p_nacional                 in varchar2 default null,
    p_continente               in number   default null,
    p_sq_pais                  in number   default null,
    p_sq_cidade                in number   default null,
    p_sq_moeda                 in number   default null,
    p_tipo_diaria              in varchar2 default null,
    p_categoria                in number   default null,
    p_valor                    in number   default null,
    p_chave                    in number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_valor_diaria (sq_valor_diaria, cliente, nacional, continente, 
                  sq_pais, sq_cidade, sq_moeda, tipo_diaria, valor, sq_categoria_diaria)
      (select sq_valor_diaria.nextval, p_cliente, p_nacional, p_continente, p_sq_pais,
              p_sq_cidade, p_sq_moeda, p_tipo_diaria, p_valor, p_categoria 
         from dual
      );
   Elsif p_operacao = 'A' Then
      --Altera registro
      update pd_valor_diaria set
         nacional            = p_nacional,
         continente          = p_continente,       
         sq_pais             = p_sq_pais, 
         sq_cidade           = p_sq_cidade,
         sq_moeda            = p_sq_moeda,  
         tipo_diaria         = p_tipo_diaria,
         valor               = p_valor,
         sq_categoria_diaria = p_categoria
       where sq_valor_diaria = p_chave;
   Elsif p_operacao = 'E' Then
      --Exclui registro
      delete pd_valor_diaria where sq_valor_diaria = p_chave;
   End If;
end SP_PutValorDiaria;
/
