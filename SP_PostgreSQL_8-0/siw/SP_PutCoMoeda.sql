create or replace FUNCTION SP_PutCoMoeda
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_codigo                    varchar,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_simbolo                   varchar,
    p_tipo                      varchar,
    p_exclusao_ptax                 date,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_moeda (sq_moeda, nome, codigo, sigla, simbolo, tipo, exclusao_ptax, ativo)
         (select Nvl(p_Chave, nextVal('sq_moeda')),
                 trim(upper(p_nome)),
                 trim(p_codigo),                 
                 p_sigla,
                 p_simbolo,
                 p_tipo,
                 p_exclusao_ptax,
                 p_ativo
           
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_moeda   set 
         nome                 = trim(upper(p_nome)),
         codigo               = trim(p_codigo),
         sigla                = upper(p_sigla),
         simbolo              = p_simbolo,
         tipo                 = upper(p_tipo),
         exclusao_ptax        = p_exclusao_ptax,
         ativo                = p_ativo
      where sq_moeda    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM co_moeda where sq_moeda = p_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;