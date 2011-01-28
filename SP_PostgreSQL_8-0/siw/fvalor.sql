create or replace function fValor
  (p_Valor    varchar,             -- Valor a ser convertido
   p_Tipo     Char,                 -- Tipo da conversão: N (texto->numero) ou T (numero->texto)
   p_Precisao numeric DEFAULT NULL,-- Número desejado de casas decimais (padrão 2)
   p_Negativo varchar DEFAULT NULL,-- Exibição de números negativos: '-' ou '()' (padrão -)
   p_Moeda    varchar DEFAULT NULL -- Indicador de moeda: R$, US$ ... (padrao '')
  )  RETURNS varchar AS $$
DECLARE
  Result      varchar(100);
  w_Tipo      Char(1)      := upper(p_Tipo);
  w_Decimal   varchar(20) := lpad('0', Nvl(p_Precisao,2), '0');
  w_Negativo  varchar(2) := null;
  w_Separador varchar(2);
  w_Moeda     varchar(1)  := '';
  w_frmt      varchar(40);
  w_nlsparam  varchar(60) := null;
BEGIN
  If p_Moeda is not null Then w_Moeda := 'L';     End If;
  If p_Negativo = '()'   Then w_Negativo := 'PR'; End If;
  If w_Tipo = 'N' Then
     w_frmt      := '999999999999D'||w_decimal;
     w_nlsparam  := 'NLS_NUMERIC_CHARACTERS = ''.,''';
  Else
     w_frmt      := w_Moeda||'999G999G999G990D'||w_decimal||Nvl(w_Negativo,'');
     If p_Moeda is null Then
        w_nlsparam  := 'NLS_NUMERIC_CHARACTERS = '',.''';
     Else
        w_nlsparam  := 'NLS_NUMERIC_CHARACTERS = '',.'' NLS_CURRENCY = '''||p_Moeda||'''';
     End If;
  End If;
  Result      := to_char(p_Valor, w_frmt, w_nlsparam);
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;