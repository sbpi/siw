create or replace FUNCTION SP_PutValorDiaria
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_nacional                 varchar,
    p_continente               numeric,
    p_sq_pais                  numeric,
    p_sq_cidade                numeric,
    p_sq_moeda                 numeric,
    p_tipo_diaria              varchar,
    p_categoria                numeric,
    p_valor                    numeric,
    p_chave                    numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM pd_valor_diaria where sq_valor_diaria = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;