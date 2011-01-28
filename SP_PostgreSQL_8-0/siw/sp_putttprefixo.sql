create or replace FUNCTION SP_PutTTPrefixo
   (p_operacao    varchar,
    p_chave       numeric,
    p_prefixo     varchar,
    p_localidade  varchar,
    p_sigla       varchar,
    p_uf          varchar,
    p_ddd         varchar,
    p_controle     varchar,
    p_degrau      varchar  
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
     insert into tt_prefixos 
       (sq_prefixo, prefixo, localidade, sigla, uf, ddd, controle, degrau)                       
       (select sq_prefixo.nextVal, p_prefixo, p_localidade, p_sigla, p_uf, p_ddd, p_controle, p_degrau);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update tt_prefixos
         set sq_prefixo = p_chave,
             prefixo = p_prefixo,
             localidade = p_localidade,
             sigla = p_sigla,
             uf = p_uf,
             ddd = p_ddd,
             controle = p_controle,
             degrau = p_degrau
       where sq_prefixo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM tt_prefixos
      where sq_prefixo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;