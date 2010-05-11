create or replace trigger TG_GP_FERIAS_IN_UP
  before insert or update on gp_ferias  
  for each row
declare
  w_sg_tramite siw_tramite.sigla%type;
begin
  -- Recupera o tr�mite da solicita��o
  select sigla into w_sg_tramite 
    from siw_tramite a
         inner join siw_solicitacao b on (a.sq_siw_tramite = b.sq_siw_tramite)
   where b.sq_siw_solicitacao = :new.sq_siw_solicitacao;
  
  -- Calcula o gozo previsto
  :new.gozo_previsto := :new.fim_data - :new.inicio_data + 1;
  
  -- Se a solicita��o est� diferente de conclu�da, grava o gozo efetivo
  If w_sg_tramite <> 'AT' Then
     :new.gozo_efetivo := :new.gozo_previsto;
  End If;
end TG_GP_FERIAS_IN_UP;
/
