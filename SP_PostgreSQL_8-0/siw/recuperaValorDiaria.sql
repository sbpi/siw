create or replace function recuperaValorDiaria
      (p_cliente   numeric, 
       p_cidade    numeric, 
       p_tipo      varchar, 
       p_categoria numeric
      )  RETURNS numeric AS $$
DECLARE

  w_existe numeric(10);
  Result   numeric      := null;
  
   c_cidade CURSOR FOR
    select a.sq_valor_diaria 
      from pd_valor_diaria a 
     where a.cliente             = p_cliente 
       and a.sq_cidade           = p_cidade
       and a.tipo_diaria         = p_tipo
       and a.sq_categoria_diaria = p_categoria;

   c_pais CURSOR FOR
    select a.sq_valor_diaria 
      from pd_valor_diaria      a
           inner join co_cidade b on (a.sq_pais = b.sq_pais)
     where a.cliente             = p_cliente 
       and a.sq_cidade           is null
       and a.tipo_diaria         = p_tipo 
       and a.sq_categoria_diaria = p_categoria
       and b.sq_cidade           = p_cidade;

   c_continente CURSOR FOR
    select a.sq_valor_diaria 
      from pd_valor_diaria a,
           co_cidade          b
           inner join co_pais c on (b.sq_pais = c.sq_pais)
     where a.cliente             = p_cliente 
       and a.sq_cidade           is null
       and a.sq_pais             is null
       and a.tipo_diaria         = p_tipo 
       and a.sq_categoria_diaria = p_categoria
       and b.sq_cidade           = p_cidade
       and a.continente          = c.continente;

BEGIN
  -- Verifica se a cidade informada existe
  select count(sq_cidade) into w_existe from co_cidade where sq_cidade = p_cidade;
  If w_existe = 0 Then return 0; End If;
  
  -- Verifica se a cidade tem valor padrão para o tipo de diária informado
  for crec in c_cidade loop result := crec.sq_valor_diaria; end loop;
  
  If result is null Then
     -- Verifica o valor para o país do tipo de diária informado
     for crec in c_pais loop result := crec.sq_valor_diaria; end loop;
     
     If result is null Then
        -- Verifica o valor para o continenente do tipo de diária informado
        for crec in c_continente loop result := crec.sq_valor_diaria; end loop;
        
        If result is null Then
           -- Erro
           Result := 0;
        End If;
     End If;
  End If;

  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;