create or replace trigger TG_SIW_SOLIC_UP
  before update on siw_solicitacao  
  for each row
declare
  w_tramite_new varchar2(20);
  w_tramite_old varchar2(20);
  w_sigla_menu  varchar2(20);
  w_tipo        number(1);
  
  cursor c_lancamento_rubrica (l_solicitacao in number) is
    -- Retorna os dados das rubricas ligadas aos documentos da solicitação
    select c.sq_rubrica_origem, c.sq_rubrica_destino, c.valor
      from fn_lancamento           a,
           fn_lancamento_doc       b,
           fn_lancamento_rubrica   c
     where a.sq_siw_solicitacao = l_solicitacao
       and (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       and (b.sq_lancamento_doc  = c.sq_lancamento_doc);
     
  cursor c_lancamento_item (l_solicitacao in number) is
    -- Retorna os dados das rubricas ligadas aos documentos da solicitação
    select c.sq_projeto_rubrica, c.valor_total
      from fn_lancamento           a,
           fn_lancamento_doc       b,
           fn_documento_item       c
     where a.sq_siw_solicitacao = l_solicitacao
       and (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       and (b.sq_lancamento_doc  = c.sq_lancamento_doc);

begin
  -- Se houve alteraçao do trâmite atual
  If :old.sq_siw_tramite <> :new.sq_siw_tramite Then
     -- Recupera a sigla do serviço
     select sigla into w_sigla_menu from siw_menu where sq_menu = :new.sq_menu;
     
     -- Recupera a sigla do tramite antigo e novo
     select nvl(sigla,'---') into w_tramite_new from siw_tramite where sq_siw_tramite = :new.sq_siw_tramite;
     select nvl(sigla,'---') into w_tramite_old from siw_tramite where sq_siw_tramite = :old.sq_siw_tramite;

     -- Se for lançamento financeiro e a solicitação está sendo concluída ou se estava concluída
     If substr(w_sigla_menu,1,2)='FN' and (w_tramite_new = 'AT' or w_tramite_old = 'AT') Then
        -- Recupera o tipo do lançamento financeiro
        select tipo into w_tipo from fn_lancamento where sq_siw_solicitacao = :new.sq_siw_solicitacao;
      
        -- Se o tipo for diferente de nulo
        If w_tipo is not null Then
           -- Se a solicitação está sendo concluída, soma nos valores reais os valores dos documentos
           If w_tramite_new = 'AT' Then
              If w_tipo < 5 Then
                 -- Se for lançamento de receita, verifica o tipo e executa a ação correspondente
                 for crec in c_lancamento_rubrica(:new.sq_siw_solicitacao) loop
                    If w_tipo = 1 Then
                       -- Dotaçao inicial: incrementa o valor do lancamento no valor inicial e na entrada real da rubrica
                       update pj_rubrica set valor_inicial = valor_inicial + crec.valor, entrada_real = entrada_real + crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                    Elsif w_tipo = 2 Then
                       -- Transferência entre rubricas: incrementa a saída real na rubrica de origem e a entrada real na rubrica de destino
                       update pj_rubrica set saida_real   = saida_real   + crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                       update pj_rubrica set entrada_real = entrada_real + crec.valor where sq_projeto_rubrica = crec.sq_rubrica_destino;
                    Elsif w_tipo = 3 or w_tipo = 4 Then
                       -- Aplicação financeira e Entradas. O valor é somado com a entrada real.
                       update pj_rubrica set entrada_real = entrada_real + crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                    End If;
                 end loop;
              Else
                 -- Se for lançamento de despesa, incrementa o valor do lancamento na saída real da rubrica
                 for crec in c_lancamento_item(:new.sq_siw_solicitacao) loop
                    update pj_rubrica set saida_real = saida_real + crec.valor_total where sq_projeto_rubrica = crec.sq_projeto_rubrica;
                 end loop;
              End If;
                               
           -- Se a solicitação estava concluída, subtrai dos valores reais os valores dos documentos
           Elsif w_tramite_old = 'AT' Then
              If w_tipo < 5 Then
                 -- Se for lançamento de receita, verifica o tipo e executa a ação correspondente
                 for crec in c_lancamento_rubrica(:new.sq_siw_solicitacao) loop
                    If w_tipo = 1 Then
                       -- Dotaçao inicial: subtrai o valor do lancamento no valor inicial e na entrada real da rubrica
                       update pj_rubrica set valor_inicial = valor_inicial - crec.valor, entrada_real = entrada_real - crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                    Elsif w_tipo = 2 Then
                       -- Transferência entre rubricas: subtrai a saída real na rubrica de origem e a entrada real na rubrica de destino
                       update pj_rubrica set saida_real   = saida_real   - crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                       update pj_rubrica set entrada_real = entrada_real - crec.valor where sq_projeto_rubrica = crec.sq_rubrica_destino;
                    Elsif w_tipo = 3 or w_tipo = 4 Then
                       -- Aplicação financeira e Entradas. O valor é subtraído da entrada real.
                       update pj_rubrica set entrada_real = entrada_real - crec.valor where sq_projeto_rubrica = crec.sq_rubrica_origem;
                    End If;
                 end loop;
              Else
                 -- Se for lançamento de despesa, subtrai o valor do lancamento na saída real da rubrica
                 for crec in c_lancamento_item(:new.sq_siw_solicitacao) loop
                    update pj_rubrica set saida_real = saida_real - crec.valor_total where sq_projeto_rubrica = crec.sq_projeto_rubrica;
                 end loop;
              End If;
           End If;
        End If;
     End If;
  End If;
end TG_SIW_SOLIC_UP;
/
