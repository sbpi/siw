CREATE OR REPLACE FUNCTION siw.PD_CriaParametro(p_cliente numeric, p_inicio date)
  RETURNS character varying AS
$BODY$declare
   w_ano        numeric(4);
   w_sequencial numeric(18) := 0;
   w_existe     numeric(4);
   p_codigo_interno  varchar(20);
   w_reg        siw.pd_parametro%rowtype;
begin

 
  -- Verifica se existe um registro criado para o cliente.
  select count(*) into w_existe from siw.pd_parametro where cliente = p_cliente;
  If w_existe = 0 Then
     insert into siw.pd_parametro 
            (cliente,   sequencial, ano_corrente,              prefixo, sufixo, limite_unidade) 
     values (p_cliente, 0,          cast (extract (year from current_date)as numeric(4)),  'PD-',   null,   'N');
  End If;
  
  -- Recupera os par�metros do cliente informado
  select * into w_reg from siw.pd_parametro where cliente = p_cliente;

  -- Se o ano da miss�o for menor que o ano corrente, configura um valor qualquer
  -- que ser� corrigido depois. Caso contr�rio, usa o sequencial
  -- armazenado em pd_parametro.
  If cast (extract (year from current_date)as numeric(4)) < w_reg.ano_corrente Then

     -- Configura o ano da miss�o para o ano informado na data de in�cio
     -- e usa um sequencial qualquer, que ser� ajustado depois
     w_ano        := cast (extract (year from p_inicio)as numeric(4));
     w_sequencial := 0;
  Else
     -- Verifica se h� necessidade de reinicializar o sequencial em fun��o da troca do ano
     If cast (extract (year from current_date)as numeric(4)) > w_reg.ano_corrente Then
        -- Configura o ano da miss�o para o ano informado corrente
        w_ano        := cast (extract (year from current_date)as numeric(4));
        w_sequencial := 1;
     Else
        w_ano        := w_reg.ano_corrente;
        w_sequencial := w_reg.sequencial + 1;
     End If;

     -- Atualiza a tabela de par�metros
     Update siw.pd_parametro Set
         ano_corrente = w_ano,
         sequencial   = w_sequencial
     Where cliente    = p_cliente;
  End If;

  --  Retorna o sequencial a ser usado na miss�o
  p_codigo_interno := COALESCE(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||COALESCE(w_reg.sufixo,'');
  return p_codigo_interno;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.PD_CriaParametro(p_cliente numeric, p_inicio date) OWNER TO siw;
