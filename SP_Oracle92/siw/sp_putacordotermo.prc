create or replace procedure SP_PutAcordoTermo
   (p_operacao            in varchar2,
    p_chave               in number,
    p_atividades          in varchar2 default null,
    p_produtos            in varchar2 default null,
    p_requisitos          in varchar2 default null,
    p_vincula_projeto     in varchar2 default null,
    p_vincula_demanda     in varchar2 default null,
    p_vincula_viagem      in varchar2 default null,
    p_prestacao_contas    in varchar2 default null,
    p_codigo_externo      in varchar2 default null
   ) is
begin
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
   Where sq_siw_solicitacao = p_chave;
end SP_PutAcordoTermo;
/
