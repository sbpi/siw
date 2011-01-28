create type rec_menu AS (sq_menu_destino numeric ,sq_menu_origem numeric ,sq_menu_pai_origem numeric );

CREATE OR REPLACE FUNCTION SG_GeraCliMod(numeric, numeric) RETURNS VOID AS $$
declare
  i       numeric(10) := 0;
  w_chave numeric(10);
 
  tb_menu rec_menu%ROWTYPE;

  type tb_menu_pai is table of numeric(10) index by binary_integer;

  w_menu     tb_menu;
  w_menu_pai tb_menu_pai;

begin
  -- Verifica se existe um registro criado para o cliente.
  select count(*) into w_existe from pd_parametro where cliente = $1;
  If w_existe = 0 Then
     insert into pd_parametro 
            (cliente,   sequencial, ano_corrente,              prefixo, sufixo, limite_unidade) 
     values ($1, 0,          cast (extract (year from current_date)as numeric(4)),  'PD-',   null,   'N');
  End If;
  
  -- Recupera os parâmetros do cliente informado
  select * into w_reg from pd_parametro where cliente = $1;

  -- Se o ano da missão for menor que o ano corrente, configura um valor qualquer
  -- que será corrigido depois. Caso contrário, usa o sequencial
  -- armazenado em pd_parametro.
  If cast (extract (year from current_date)as numeric(4)) < w_reg.ano_corrente Then

     -- Configura o ano da missão para o ano informado na data de início
     -- e usa um sequencial qualquer, que será ajustado depois
     w_ano        := cast (extract (year from $2)as numeric(4));
     w_sequencial := 0;
  Else
     -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
     If cast (extract (year from current_date)as numeric(4)) > w_reg.ano_corrente Then
        -- Configura o ano da missão para o ano informado corrente
        w_ano        := cast (extract (year from current_date)as numeric(4));
        w_sequencial := 1;
     Else
        w_ano        := w_reg.ano_corrente;
        w_sequencial := w_reg.sequencial + 1;
     End If;

     -- Atualiza a tabela de parâmetros
     Update pd_parametro Set
         ano_corrente = w_ano,
         sequencial   = w_sequencial
     Where cliente    = $1;
  End If;

  --  Retorna o sequencial a ser usado na missão
  p_codigo_interno := COALESCE(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||COALESCE(w_reg.sufixo,'');
  return p_codigo_interno;
END $$ LANGUAGE 'plpgsql' VOLATILE;
