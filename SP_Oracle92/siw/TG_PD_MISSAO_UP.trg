create or replace trigger TG_PD_MISSAO_UP
  before update on pd_missao  
  for each row
DECLARE  
  w_valor_alimentacao number(18,2);
  w_valor_passagem    number(18,2);
begin
  if coalesce(:new.valor_alimentacao,0) = 0 then
     select sum(nvl(x.valor,0))+sum(nvl(x.hospedagem_valor,0))-sum(nvl((x.veiculo_valor*x.valor/100),0))
       into w_valor_alimentacao
       from pd_diaria x
      where x.sq_siw_solicitacao = :new.sq_siw_solicitacao;

     :new.valor_alimentacao := nvl(w_valor_alimentacao,:old.valor_alimentacao);
  end if;

  if coalesce(:new.valor_passagem,0) = 0 then
     select sum(nvl(x.valor_bilhete,0))+sum(nvl(x.valor_pta,0))+sum(nvl(x.valor_taxa_embarque,0))
       into w_valor_passagem
       from pd_bilhete x
      where x.sq_siw_solicitacao = :new.sq_siw_solicitacao
        and x.tipo = 'S';
        
     :new.valor_passagem := nvl(w_valor_passagem,:old.valor_passagem);
  end if;

  -- Atualiza o valor da solicitação
  update siw_solicitacao a 
     set a.valor = a.valor + 
                   (:new.valor_alimentacao
                    + :new.valor_transporte 
                    + :new.valor_adicional 
                    - :new.desconto_alimentacao 
                    - :new.desconto_transporte 
                    + :new.valor_passagem
                    + :new.reembolso_valor
                   ) - 
                   (:old.valor_alimentacao
                    + :old.valor_transporte 
                    + :old.valor_adicional 
                    - :old.desconto_alimentacao 
                    - :old.desconto_transporte 
                    + :old.valor_passagem
                    + :old.reembolso_valor
                   )
  where sq_siw_solicitacao = :new.sq_siw_solicitacao;
end TG_PD_MISSAO_UP;
/
