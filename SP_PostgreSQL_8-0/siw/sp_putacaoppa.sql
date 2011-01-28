create or replace FUNCTION SP_PutAcaoPPA
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_sq_acao_ppa_pai           numeric,
    p_codigo                    varchar,
    p_nome                      varchar,
    p_responsavel               varchar,
    p_telefone                  varchar,
    p_email                     varchar,
    p_ativo                     varchar,
    p_padrao                    varchar,
    p_aprovado                  numeric,
    p_saldo                     numeric,
    p_empenhado                 numeric,
    p_liquidado                 numeric,
    p_liquidar                  numeric,
    p_selecionada_mpog          varchar,
    p_selecionada_relevante     varchar,
    p_cod_programa              varchar,
    p_cod_acao                  varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into or_acao_ppa
             (sq_acao_ppa,         sq_acao_ppa_pai,    cliente,    codigo,    nome,       responsavel, 
              telefone,            email,              ativo,      padrao,    aprovado,   saldo,
              empenhado,           liquidado,          liquidar,   selecionada_mpog, 
              selecionada_relevante
             )
      (select sq_acao_ppa.nextval, p_sq_acao_ppa_pai,  p_cliente,  p_codigo,  p_nome,     p_responsavel, 
              p_telefone,          p_email,            p_ativo,    p_padrao,  p_aprovado, p_saldo,
              p_empenhado,         p_liquidado,        p_liquidar, p_selecionada_mpog, 
              p_selecionada_relevante
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update or_acao_ppa set 
         sq_acao_ppa_pai       = p_sq_acao_ppa_pai,
         codigo                = p_codigo,
         nome                  = p_nome,
         responsavel           = p_responsavel,
         telefone              = p_telefone,
         email                 = p_email,
         ativo                 = p_ativo,
         padrao                = p_padrao,
         aprovado              = p_aprovado,
         saldo                 = p_saldo,
         empenhado             = p_empenhado,
         liquidado             = p_liquidado,
         liquidar              = p_liquidar,
         selecionada_mpog      = p_selecionada_mpog,
         selecionada_relevante = p_selecionada_relevante
       where sq_acao_ppa = p_chave;
   Elsif p_operacao = 'U' Then
      -- Altera registro
      update or_acao_ppa set 
         aprovado              = p_aprovado,
         empenhado             = p_empenhado,
         liquidado             = p_liquidado
       where sq_acao_ppa = (select a.sq_acao_ppa
                              from or_acao_ppa a inner join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                             where b.codigo  = p_cod_programa
                               and a.codigo  = p_cod_acao
                               and a.cliente = p_cliente
                           );
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM or_acao_ppa where sq_acao_ppa = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;