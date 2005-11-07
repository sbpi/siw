create or replace trigger TG_PD_MISSAO_IN_UP
  before insert or update on pd_missao
  for each row

declare
  w_chave  varchar2(60) := null;
  w_inicio date;
begin
  If INSERTING Then
     -- Recupera o ano da missão a partir da data informada em SIW_SOLICITACAO
     select inicio into w_inicio from siw_solicitacao where sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     PD_CriaParametro(:new.cliente, w_inicio, w_chave);
     :new.codigo_interno := w_chave;
  End If;
end TG_PD_MISSAO_IN_UP;
/
