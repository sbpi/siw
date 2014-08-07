create or replace procedure sp_ajustaFasePagamento(p_cliente in number, p_pessoa in number default null, p_todos in varchar2 default null) is
  w_menu        siw_menu.sq_menu%type;
  w_atual       siw_solicitacao.sq_siw_solicitacao%type;
  w_solic       siw_solicitacao.sq_siw_solicitacao%type;
  w_cadastrador co_pessoa.sq_pessoa%type;
  w_ee          siw_tramite.sq_siw_tramite%type;
  w_pp          siw_tramite.sq_siw_tramite%type;
  w_lista       varchar2(500);
  
  cursor c_dados is
     select w.sq_siw_solicitacao, w5.inclusao, trunc(w5.fim) as fim, w7.dias_prestacao_contas, 
            w1.cadastrador, w2.sq_menu, g.sq_siw_tramite as pp, h.sq_siw_tramite as ee, w5.codigo_interno
       from fn_lancamento                      w
            inner     join siw_solicitacao     w1 on (w.sq_siw_solicitacao  = w1.sq_siw_solicitacao and w1.descricao like 'Adiantamento%')
              inner   join siw_menu            w2 on (w1.sq_menu            = w2.sq_menu and 
                                                      w2.sigla              = 'FNDVIA'
                                                     )
                inner join siw_tramite         g  on (w2.sq_menu            = g.sq_menu and g.sigla = 'PP')
                inner join siw_tramite         h  on (w2.sq_menu            = h.sq_menu and h.sigla = 'EE')
                inner join pd_parametro        w3 on (w2.sq_pessoa          = w3.cliente)
              inner   join siw_tramite         i  on (w1.sq_siw_tramite     = i.sq_siw_tramite and i.sigla = 'EE')
            inner     join pd_missao           w4 on (w4.cliente            = p_cliente and
                                                      w.pessoa              = w4.sq_pessoa and 
                                                      w1.sq_solic_pai      <> w4.sq_siw_solicitacao
                                                     )
              inner   join pd_categoria_diaria w7 on (w4.diaria             = w7.sq_categoria_diaria)
              inner   join siw_solicitacao     w5 on (w4.sq_siw_solicitacao = w5.sq_siw_solicitacao)
                inner join siw_tramite         w6 on (w5.sq_siw_tramite     = w6.sq_siw_tramite and w6.sigla  in ('PC','AP'))
      where w.cliente = p_cliente        
       and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and w.pessoa = coalesce(p_pessoa,0)))
     order by w.sq_siw_solicitacao,  w1.cadastrador, w2.sq_menu, g.sq_siw_tramite, h.sq_siw_tramite, w5.inclusao;
begin
  w_atual := 0;
  w_solic := 0;
  for crec in c_dados loop
    If 0 > soma_dias(p_cliente, crec.fim, crec.dias_prestacao_contas + 1,'U') - trunc(sysdate) Then
      If w_atual = 0 and w_solic = 0 Then
         w_atual := crec.sq_siw_solicitacao;
         w_solic := crec.sq_siw_solicitacao;
      End If;
      -- Monta a lista de viagens que está bloqueando o pagamento
      If w_atual <> w_solic Then
         sp_putlancamentoenvio(
                          p_menu          => w_menu,
                          p_chave         => w_solic,
                          p_pessoa        => w_cadastrador,
                          p_tramite       => w_ee,
                          p_novo_tramite  => w_pp,
                          p_devolucao     => 'N',
                          p_despacho      => 'Bloqueio automático de pagamento: '||substr(w_lista,3)
                         );
         w_atual := crec.sq_siw_solicitacao;
         w_lista := '';
      End If;
      w_menu        := crec.sq_menu;
      w_solic       := crec.sq_siw_solicitacao;
      w_cadastrador := crec.cadastrador;
      w_ee          := crec.ee;
      w_pp          := crec.pp;
      w_lista       := w_lista||', '||crec.codigo_interno;
    End If;
  end loop;

  -- Processa o último registro
  If w_atual > 0 Then
     sp_putlancamentoenvio(
                      p_menu          => w_menu,
                      p_chave         => w_solic,
                      p_pessoa        => w_cadastrador,
                      p_tramite       => w_ee,
                      p_novo_tramite  => w_pp,
                      p_devolucao     => 'N',
                      p_despacho      => 'Bloqueio automático de pagamento: '||substr(w_lista,3)
                     );
  End If;
end sp_ajustaFasePagamento;
/
