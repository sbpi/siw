create or replace procedure PA_CriaParametro
   (p_unidade    in  number, 
    p_data       in  date,
    p_numero_doc out varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18) := 0;
   w_reg        pa_parametro%rowtype;
   w_unid       pa_unidade%rowtype;
   w_cliente    siw_cliente.sq_pessoa%type;
   w_unid_pai   number(18);
   w_existe     number(10);
begin
  -- Recupera os dados da unidade pai
  select coalesce(sq_unidade_pai,sq_unidade) into w_unid_pai from pa_unidade where sq_unidade = p_unidade;

  -- Recupera os dados da unidade numeradora e o cliente
  select * into w_unid from pa_unidade where sq_unidade = w_unid_pai;
  select b.sq_pessoa into w_cliente from pa_unidade a inner join eo_unidade b on a.sq_unidade = b.sq_unidade where a.sq_unidade = w_unid_pai;

  -- Recupera os parâmetros do cliente informado
  select * into w_reg from pa_parametro where cliente = w_unid.cliente;

  -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
  If to_char(sysdate,'yyyy') > w_reg.ano_corrente Then
     w_ano        := to_char(sysdate,'yyyy');
     w_sequencial := 1;

     -- Atualiza a tabela de parâmetros
     Update pa_parametro Set ano_corrente = w_ano Where cliente = w_unid.cliente;

     -- Atualiza a tabela de unidades
     Update pa_unidade Set numero_documento = w_sequencial Where sq_unidade = w_unid_pai;

  Elsif to_char(p_data,'yyyy') <= 2009 Then
     -- Configura o ano do acordo para o ano informado na data de início
     -- e usa um sequencial qualquer, que será ajustado depois
     w_ano        := to_number(to_char(p_data,'yyyy'));
     w_sequencial := 0;
  Else
     w_ano        := w_reg.ano_corrente;
     -- Se já houver documento com o sequencial gerado, incrementa 1 e testa novamente,
     -- até achar um número vago.
     w_sequencial := w_unid.numero_documento;
     loop
        w_sequencial := w_sequencial + 1;
        select count(a.sq_siw_solicitacao) into w_existe 
          from pa_documento a 
         where a.numero_documento = w_sequencial 
           and a.ano              = w_ano 
           and a.prefixo          = w_unid.prefixo
           and a.cliente          = w_cliente;
        if w_existe = 0 then exit; end if;
     end loop;

     -- Atualiza a tabela de unidades
     Update pa_unidade Set numero_documento = w_sequencial Where sq_unidade = w_unid_pai;

  End If;

  --  Retorna o sequencial a ser usado no lançamento
  p_numero_doc := w_unid.prefixo||'.'||substr(1000000+w_sequencial,2,6)||'/'||w_ano;
  p_numero_doc := p_numero_doc||'-'||validaCnpjCpf(p_numero_doc,'gerar');
end PA_CriaParametro;
/
