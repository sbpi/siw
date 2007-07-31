create or replace trigger TG_FN_LANCAMENTO_IN_UP
  before insert or update on fn_lancamento
  for each row
declare
begin
  If :new.sq_tipo_pessoa <> :old.sq_tipo_pessoa Then
     :new.pessoa           := null;
     :new.sq_agencia       := null;
     :new.operacao_conta   := null;
     :new.numero_conta     := null;
     :new.sq_pais_estrang  := null;
     :new.aba_code         := null;
     :new.swift_code       := null;
     :new.endereco_estrang := null;
     :new.banco_estrang    := null;
     :new.agencia_estrang  := null;
     :new.cidade_estrang   := null;
     :new.informacoes      := null;
     :new.codigo_deposito  := null;
  End If;
end TG_FN_LANCAMENTO_IN_UP;
/
