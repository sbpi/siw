create or replace procedure PD_CriaParametro
   (p_cliente        in  number, 
    p_inicio         in  date,
    p_codigo_interno out varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18) := 0;
   w_existe     number(4);
   w_reg        pd_parametro%rowtype;
begin
  -- Verifica se existe um registro criado para o cliente.
  select count(*) into w_existe from pd_parametro where cliente = p_cliente;
  If w_existe = 0 Then
     insert into pd_parametro 
            (cliente,   sequencial, ano_corrente,              prefixo, sufixo) 
     values (p_cliente, 0,          to_char(sysdate, 'yyyy'),  'AC-',   null);
  End If;
  
  -- Recupera os par�metros do cliente informado
  select * into w_reg from pd_parametro where cliente = p_cliente;

  -- Se o ano da miss�o for menor que o ano corrente, configura um valor qualquer
  -- que ser� corrigido depois. Caso contr�rio, usa o sequencial
  -- armazenado em pd_parametro.
  If to_char(p_inicio,'yyyy') < w_reg.ano_corrente Then

     -- Configura o ano da miss�o para o ano informado na data de in�cio
     -- e usa um sequencial qualquer, que ser� ajustado depois
     w_ano        := to_number(to_char(p_inicio,'yyyy'));
     w_sequencial := 0;
  Else
     -- Verifica se h� necessidade de reinicializar o sequencial em fun��o da troca do ano
     If to_char(sysdate,'yyyy') > w_reg.ano_corrente Then
        -- Configura o ano da miss�o para o ano informado corrente
        w_ano        := to_char(sysdate,'yyyy');
        w_sequencial := 1;
     Else
        w_ano        := w_reg.ano_corrente;
        w_sequencial := w_reg.sequencial + 1;
     End If;

     -- Atualiza a tabela de par�metros
     Update pd_parametro Set
         ano_corrente = w_ano,
         sequencial   = w_sequencial
     Where cliente    = p_cliente;
  End If;

  --  Retorna o sequencial a ser usado na miss�o
  p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

end PD_CriaParametro;
/
