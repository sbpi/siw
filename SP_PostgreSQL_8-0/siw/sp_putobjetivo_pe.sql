create or replace FUNCTION sp_putobjetivo_pe
   (p_operacao   varchar,
    p_chave      numeric,
    p_chave_aux  numeric,
    p_cliente    numeric,
    p_nome       varchar,
    p_sigla      varchar,
    p_descricao  varchar,
    p_codigo     varchar,
    p_ativo      varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- Recupera a próxima chave
      select sq_peobjetivo.nextval into w_chave from dual;
      
      -- Insere registro
      insert into pe_objetivo
        (sq_peobjetivo, cliente,   sq_plano, nome,   sigla,   descricao,   ativo,   codigo_externo)
      values
        (w_chave,       p_cliente, p_chave,  p_nome, p_sigla, p_descricao, p_ativo, p_codigo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pe_objetivo
         set sq_plano       = p_chave,
             nome           = p_nome,
             sigla          = p_sigla,
             descricao      = p_descricao,
             ativo          = p_ativo,
             codigo_externo = p_codigo
       where sq_peobjetivo = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pe_objetivo where sq_peobjetivo = p_chave_aux;
   Elsif p_operacao = 'T' Then
      -- Insere registro a partir do que foi indicado na tela de importação de objetivos
      insert into pe_objetivo (sq_peobjetivo, cliente,   sq_plano, nome,   sigla,   descricao,   ativo, codigo_externo)
      (select sq_peobjetivo.nextval, cliente, p_chave, nome, sigla, descricao, ativo, codigo_externo
         from pe_objetivo 
        where sq_peobjetivo = p_chave_aux
      );
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;