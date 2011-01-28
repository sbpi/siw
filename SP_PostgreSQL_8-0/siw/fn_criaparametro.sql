CREATE OR REPLACE FUNCTION fn_criaparametro(p_cliente numeric, p_vencimento date, OUT P_CODIGO_INTERNO VARCHAR) RETURNS varchar AS $$
DECLARE
   w_ano        numeric(4);
   w_sequencial numeric(18) := 0;
   w_existe     numeric(4);
   w_teste      varchar(4);
   w_reg        ac_parametro%rowtype;

BEGIN
  -- Verifica se existe um registro criado para o cliente.

  select count(1) into w_existe from fn_parametro where cliente = p_cliente;
    If w_existe = 0 Then
     insert into fn_parametro
            (cliente,sequencial,ano_corrente,prefixo,sufixo)
     values (p_cliente, 0, cast (extract (year from current_date)as numeric(4)),  'FN-',   null);
  End If;

  -- Recupera os parâmetros do cliente informado

  select * into w_reg from fn_parametro where cliente = p_cliente;

  -- Se o ano do acordo for menor que o ano corrente, configura um valor qualquer
  -- que será corrigido depois. Caso contrário, usa o sequencial
  -- armazenado em AC_PARAMETRO.
	  If cast (extract (year from p_vencimento)as numeric(4)) < w_reg.ano_corrente Then

	     -- Configura o ano do acordo para o ano informado na data de início
	     -- e usa um sequencial qualquer, que será ajustado depois
	     w_ano        := cast (extract (year from p_vencimento)as numeric(4));
	     w_sequencial := 0;
	  Else

	     If cast (extract (year from current_date)as numeric(4)) > w_reg.ano_corrente Then
		-- Configura o ano do acordo para o ano informado corrente
		w_ano        := cast (extract (year from current_date)as numeric(4));
		w_sequencial := 1;
	     Else
		w_ano        := w_reg.ano_corrente;
		w_sequencial := w_reg.sequencial + 1;
	     End If;

	     -- Atualiza a tabela de parâmetros
	     Update fn_parametro Set
		 ano_corrente = w_ano,
		 sequencial   = w_sequencial
	     Where cliente    = p_cliente;
	  End If;
 

   p_codigo_interno := COALESCE(w_reg.prefixo,'') ||w_sequencial||'/'||w_ano||COALESCE(w_reg.sufixo,'');
END; $$ LANGUAGE 'plpgsql' VOLATILE;