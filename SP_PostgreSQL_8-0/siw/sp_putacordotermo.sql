create or replace FUNCTION SP_PutAcordoTermo
   (p_operacao            varchar,
    p_chave               numeric,
    p_atividades          varchar,
    p_produtos            varchar,
    p_requisitos          varchar,
    p_vincula_projeto     varchar,
    p_vincula_demanda     varchar,
    p_vincula_viagem      varchar,
    p_prestacao_contas    varchar,
    p_codigo_externo      varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Atualiza a solicitação com o código externo
   Update siw_solicitacao set codigo_externo = p_codigo_externo where sq_siw_solicitacao = p_chave;
   
   -- Atualiza o registro do acordo com os dados da conclusão.
   Update ac_acordo set
      atividades       = p_atividades,
      produtos         = p_produtos,
      requisitos       = p_requisitos,
      vincula_projeto  = Nvl(p_vincula_projeto,'S'),
      vincula_demanda  = Nvl(p_vincula_demanda,'S'),
      vincula_viagem   = Nvl(p_vincula_viagem,'S'),
      prestacao_contas = Nvl(p_prestacao_contas,'N')
   Where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;