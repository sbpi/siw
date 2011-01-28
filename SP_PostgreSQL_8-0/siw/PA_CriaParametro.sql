﻿CREATE OR REPLACE FUNCTION PA_CriaParametro(p_unidade numeric, p_numero_doc out varchar) RETURNS varchar AS $$
declare
   w_ano        numeric(4);
   w_sequencial numeric(18) := 0;
   w_reg        pa_parametro%rowtype;
   w_unid       pa_unidade%rowtype;
   w_unid_pai   numeric(18);
   w_existe     numeric(10);

begin
  -- Recupera os dados da unidade pai
  select coalesce(sq_unidade_pai,sq_unidade) into w_unid_pai from pa_unidade where sq_unidade = p_unidade;

  -- Recupera os dados da unidade numeradora
  select * into w_unid from pa_unidade where sq_unidade = w_unid_pai;

  -- Recupera os parâmetros do cliente informado
  select * into w_reg from pa_parametro where cliente = w_unid.cliente;

  -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
  If cast (extract (year from current_date)as numeric(4)) > w_reg.ano_corrente Then
     w_ano        := cast (extract (year from current_date)as numeric(4));
     w_sequencial := 1;
  Else
     w_ano        := w_reg.ano_corrente;
     -- Se já houver documento com o sequencial gerado, incrementa 1 e testa novamente,
     -- até achar um número vago.
     loop
        w_sequencial := w_unid.numero_documento + 1;
        select count(a.sq_siw_solicitacao) into w_existe 
          from pa_documento a 
         where a.numero_documento = w_sequencial 
           and a.ano              = w_ano 
           and a.prefixo          = w_unid.prefixo;
        if w_existe = 0 then exit; end if;
     end loop;
  End If;

  -- Atualiza a tabela de parâmetros
  Update pa_parametro Set ano_corrente = w_ano Where cliente = w_unid.cliente;

  -- Atualiza a tabela de unidades
  Update pa_unidade Set numero_documento = w_sequencial Where sq_unidade = w_unid_pai;

  --  Retorna o sequencial a ser usado no lançamento
  p_numero_doc := w_unid.prefixo||'.'||SUBSTRING('1000000'||cast(w_sequencial as varchar) from 2 for 8)||'/'||w_ano;
  p_numero_doc := p_numero_doc||'-'||validaCnpjCpf(p_numero_doc,'gerar');
END $$ LANGUAGE 'plpgsql' VOLATILE;
