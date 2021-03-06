create or replace procedure SP_PutAcordoTermo
   (p_operacao            in varchar2,
    p_chave               in number,
    p_atividades          in varchar2 default null,
    p_produtos            in varchar2 default null,
    p_requisitos          in varchar2 default null,
    p_vincula_projeto     in varchar2 default null,
    p_vincula_demanda     in varchar2 default null,
    p_vincula_viagem      in varchar2 default null,
    p_codigo_externo      in varchar2 default null
   ) is
begin
   -- Atualiza o registro da demanda com os dados da conclus�o.
   Update ac_acordo set
      atividades      = p_atividades,
      produtos        = p_produtos,
      requisitos      = p_requisitos,
      vincula_projeto = Nvl(p_vincula_projeto,'S'),
      vincula_demanda = Nvl(p_vincula_demanda,'S'),
      vincula_viagem  = Nvl(p_vincula_viagem,'S'),
      codigo_externo  = p_codigo_externo
   Where sq_siw_solicitacao = p_chave;
end SP_PutAcordoTermo;
/

