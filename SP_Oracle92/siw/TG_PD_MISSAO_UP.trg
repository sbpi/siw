create or replace trigger TG_PD_MISSAO_UP
  before update on pd_missao  
  for each row
begin
  -- Atualiza o valor da solicitação
  update siw_solicitacao a 
     set a.valor = a.valor + 
                   (:new.valor_alimentacao
                    + :new.valor_transporte 
                    + :new.valor_adicional 
                    - :new.desconto_alimentacao 
                    - :new.desconto_transporte 
                    + :new.valor_passagem
                   ) - 
                   (:old.valor_alimentacao
                    + :old.valor_transporte 
                    + :old.valor_adicional 
                    - :old.desconto_alimentacao 
                    - :old.desconto_transporte 
                    + :old.valor_passagem
                   )
  where sq_siw_solicitacao = :new.sq_siw_solicitacao;
end TG_PD_MISSAO_UP;
/
