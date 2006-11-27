create or replace trigger TG_FN_LANCAMENTO_RUBRICA
  before insert or delete on fn_lancamento_rubrica  
  for each row
declare
  w_tipo       number(1);
  w_lancamento number(18);
begin
  -- Identificação do tipo do lançamento financeiro, que determina o funcionamento da trigger
  If DELETING Then
     w_lancamento := :old.sq_lancamento_doc;
  Else
     w_lancamento := :new.sq_lancamento_doc;
  End If;
  select tipo into w_tipo 
    from fn_lancamento 
   where sq_siw_solicitacao = (select sq_siw_solicitacao from fn_lancamento_doc where sq_lancamento_doc = w_lancamento);

  -- Se o item estiver ligado a uma rubrica, atualiza a saída prevista da rubrica.
  If INSERTING Then
    If w_tipo = 1 Then
      -- Dotação inicial. O valor é somado com a entrada prevista.
      update pj_rubrica set entrada_prevista = entrada_prevista + :new.valor where sq_projeto_rubrica = :new.sq_rubrica_origem;
    Elsif w_tipo = 2 Then
      -- Transferência de valores entre rubricas. O valor é somado na saida prevista da rubrica origem e 
      -- somado na entrada prevista da rubrica destino.
      update pj_rubrica set saida_prevista   = saida_prevista   + :new.valor where sq_projeto_rubrica = :new.sq_rubrica_origem;
      update pj_rubrica set entrada_prevista = entrada_prevista + :new.valor where sq_projeto_rubrica = :new.sq_rubrica_destino;
    Elsif w_tipo = 3 or w_tipo = 4 Then
      -- Aplicação financeira e Entradas. O valor é somado com a entrada prevista.
      update pj_rubrica set entrada_prevista = entrada_prevista + :new.valor where sq_projeto_rubrica = :new.sq_rubrica_origem;
    End If;
  Elsif DELETING Then
    If w_tipo = 1 Then
      -- Dotação inicial. O valor é subtraído da entrada prevista.
      update pj_rubrica set entrada_prevista = entrada_prevista - :old.valor where sq_projeto_rubrica = :old.sq_rubrica_origem;
    Elsif w_tipo = 2 Then
      -- Transferência de valores entre rubricas. O valor é retirado da saida prevista da rubrica origem e 
      -- da entrada prevista da rubrica destino.
      update pj_rubrica set saida_prevista   = saida_prevista   - :old.valor where sq_projeto_rubrica = :old.sq_rubrica_origem;
      update pj_rubrica set entrada_prevista = entrada_prevista - :old.valor where sq_projeto_rubrica = :old.sq_rubrica_destino;
    Elsif w_tipo = 3  or w_tipo = 4 Then
      -- Aplicação financeira e Entradas. O valor é subtraído da entrada prevista.
      update pj_rubrica set entrada_prevista = entrada_prevista - :old.valor where sq_projeto_rubrica = :old.sq_rubrica_origem;
    End If;
  End If;
  
end TG_FN_LANCAMENTO_RUBRICA;
/
