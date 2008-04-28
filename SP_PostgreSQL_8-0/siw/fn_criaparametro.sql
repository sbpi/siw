
CREATE OR REPLACE FUNCTION siw.fn_criaparametro(p_cliente numeric, p_vencimento date)
  RETURNS character varying AS
$BODY$
/*
Tipo = 1 => Converte acentos formato Benner (Paradox Intl) para ASCII Ansi
Tipo diferente de 1 ou nulo => Retira caracteres acentuados e converte para minúsculas
                               para ordenação no SELECT
*/
DECLARE
   w_ano        numeric(4);
   w_sequencial numeric(18) := 0;
   w_existe     numeric(4);
   w_teste      varchar(4);
   w_reg        siw.ac_parametro%rowtype;
   p_codigo_interno Varchar(10);

BEGIN
  -- Verifica se existe um registro criado para o cliente.

  select count(1) into w_existe from siw.fn_parametro where cliente = p_cliente;
    If w_existe = 0 Then
     insert into siw.fn_parametro
            (cliente,sequencial,ano_corrente,prefixo,sufixo)
     values (p_cliente, 0, cast (extract (year from current_date)as numeric(4)),  'FN-',   null);
  End If;

  -- Recupera os parâmetros do cliente informado

  select * into w_reg from siw.fn_parametro where cliente = p_cliente;




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
	     Update siw.fn_parametro Set
		 ano_corrente = w_ano,
		 sequencial   = w_sequencial
	     Where cliente    = p_cliente;
	  End If;
 

   p_codigo_interno := COALESCE(w_reg.prefixo,'') ||w_sequencial||'/'||w_ano||COALESCE(w_reg.sufixo,'');

   RETURN p_codigo_interno;
END; $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.fn_criaparametro(p_cliente numeric, p_vencimento date) OWNER TO siw;