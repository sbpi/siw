create or replace FUNCTION sp_PutTipoDemanda
   (p_operacao          varchar,
    p_chave             numeric,
    p_cliente           numeric,
    p_nome              varchar,
    p_sigla             varchar,
    p_descricao         varchar,
    p_unidade           numeric,
    p_reuniao           varchar,
    p_ativo             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gd_demanda_tipo (sq_demanda_tipo, cliente, nome, sigla, descricao, sq_unidade, reuniao, ativo)
      (select sq_demanda_tipo.nextval, p_cliente, p_nome, upper(p_sigla), p_descricao, p_unidade, p_reuniao, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gd_demanda_tipo
         set cliente          = p_cliente,
             nome             = p_nome,
             sigla            = upper(p_sigla),
             descricao        = p_descricao,
             sq_unidade       = p_unidade,
             reuniao          = p_reuniao,
             ativo            = p_ativo
       where sq_demanda_tipo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM gd_demanda_tipo
       where sq_demanda_tipo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;