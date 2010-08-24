create or replace procedure sp_ajustaFasePagamento(p_cliente in number, p_pessoa in number default null, p_todos in varchar2 default null) is
  cursor c_dados is
     -- Recupera pagamentos de diárias em execução, de pessoas com pendência de prestação de contas de viagens
     select w.sq_siw_solicitacao, w1.cadastrador, w2.sq_menu, g.sq_siw_tramite as pp, h.sq_siw_tramite as ee, count(*) as atraso_pc
       from fn_lancamento                      w
            inner     join siw_solicitacao     w1 on (w.sq_siw_solicitacao  = w1.sq_siw_solicitacao)
              inner   join siw_menu            w2 on (w1.sq_menu            = w2.sq_menu and 
                                                      w2.sigla              = 'FNDVIA' and
                                                      w2.sq_pessoa          = p_cliente
                                                     )
                inner join siw_tramite         g  on (w2.sq_menu            = g.sq_menu and g.sigla = 'PP')
                inner join siw_tramite         h  on (w2.sq_menu            = h.sq_menu and h.sigla = 'EE')
                inner join pd_parametro        w3 on (w2.sq_pessoa          = w3.cliente)
              inner   join siw_tramite         i  on (w1.sq_siw_tramite     = i.sq_siw_tramite and i.sigla = 'EE')
            inner     join pd_missao           w4 on (w.pessoa              = w4.sq_pessoa and w1.sq_solic_pai <> w4.sq_siw_solicitacao)
              inner   join pd_categoria_diaria w7 on (w4.diaria             = w7.sq_categoria_diaria)
              inner   join siw_solicitacao     w5 on (w4.sq_siw_solicitacao = w5.sq_siw_solicitacao)
                inner join siw_tramite         w6 on (w5.sq_siw_tramite     = w6.sq_siw_tramite)
      where w6.sigla in ('PC','AP')
        and 0        > soma_dias(w2.sq_pessoa,trunc(w5.fim),w7.dias_prestacao_contas + 1,'U') - trunc(sysdate)
       and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and w.pessoa = coalesce(p_pessoa,0)))
     group by w.sq_siw_solicitacao, w1.cadastrador, w2.sq_menu, g.sq_siw_tramite, h.sq_siw_tramite;
begin
  for crec in c_dados loop
     -- Coloca os pagamentos pendentes de prestação de contas
     sp_putlancamentoenvio(
                      p_menu          => crec.sq_menu,
                      p_chave         => crec.sq_siw_solicitacao,
                      p_pessoa        => crec.cadastrador,
                      p_tramite       => crec.ee,
                      p_novo_tramite  => crec.pp,
                      p_devolucao     => 'N',
                      p_despacho      => 'Bloqueio automático de pagamento.'
                     );
  end loop;
end sp_ajustaFasePagamento;
/
