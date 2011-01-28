create or replace FUNCTION SP_PutRespAcao
   (p_chave               numeric,
    p_responsavel         varchar,
    p_telefone            varchar,
    p_email               varchar,
    p_tipo                numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_tipo = 1 or p_tipo = 2 Then
      -- Atualiza a tabela de açoes do PPA
      Update or_acao_ppa set
          responsavel      = p_responsavel,
          telefone         = p_telefone,
          email            = p_email
      where sq_acao_ppa    = p_chave;
   Elsif p_tipo = 3 Then
      Update or_prioridade set
           responsavel       = p_responsavel,
           telefone          = p_telefone,
           email             = p_email
       where sq_orprioridade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;