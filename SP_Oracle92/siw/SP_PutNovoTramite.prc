create or replace procedure SP_PutNovoTramite
   (p_menu                in number,
    p_solic               in number,
    p_pessoa              in number,
    p_tramiteAtual        in number,
    p_tramiteNovo         in number, 
    p_destinatario        in number   default null,
    p_tipo_log            in number   default null,
    p_despacho            in varchar2 default null, 
    p_observacao          in varchar2 default null, 
    p_justificativa1      in varchar2 default null, 
    p_justificativa2      in varchar2 default null
   ) is
   w_menu          siw_menu%rowtype;
   w_modulo        siw_modulo%rowtype;
   w_solic         siw_solicitacao%rowtype;
   w_chave_log     number(18) := null;
   w_chave_dem     number(18) := null;
   w_tramiteAtual  siw_tramite%rowtype;
   w_tramiteNovo   siw_tramite%rowtype;
   
   w_cont          number(18);

begin
   -- Recupera os dados do serviço, do módulo e dos trâmites atual e novo
   select * into w_menu         from siw_menu        where sq_menu        = p_menu;
   select * into w_modulo       from siw_modulo      where sq_modulo      = w_menu.sq_modulo;
   select * into w_tramiteAtual from siw_tramite     where sq_siw_tramite = p_tramiteAtual;
   select * into w_tramiteNovo  from siw_tramite     where sq_siw_tramite = p_tramiteNovo;
   select * into w_solic        from siw_solicitacao where sq_siw_solicitacao = p_solic;

   If w_tramiteNovo.sigla = 'PP' and w_menu.sigla = 'FNDVIA' Then
      -- Pagamento de diária de beneficiário sem pendência na prestação de contas vai salta para o trâmite EE (Pagamento)
      select count(*) into w_cont
        from pd_missao                        a
             inner   join pd_categoria_diaria f on (a.diaria              = f.sq_categoria_diaria)
             inner   join siw_solicitacao     b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
               inner join siw_tramite         c on (b.sq_siw_tramite      = c.sq_siw_tramite and
                                                    c.sigla               in ('VP','PC','AP')
                                                   )
               inner join siw_menu            d on (b.sq_menu             = d.sq_menu)
               inner join pd_parametro        e on (d.sq_pessoa           = e.cliente)
       where 0           > soma_dias(e.cliente,trunc(b.fim),f.dias_prestacao_contas + 1,'U') - trunc(sysdate)
         and 0           < (select count(*)
                              from siw_solicitacao        w
                                   inner join siw_tramite x on (w.sq_menu = x.sq_menu)
                             where w.sq_siw_solicitacao = p_solic
                               and x.sigla              = 'PP'
                            )
         and a.sq_pessoa = (select pessoa from fn_lancamento where sq_siw_solicitacao = p_solic);
  
      -- Se não houver pendência, coloca o lançamento na fase de pagamento (última antes de estar concluída).
      If w_cont = 0 Then
         select * into w_tramiteNovo
            from siw_tramite a
           where a.sq_menu = p_menu
             and a.sigla   = 'EE';
      End If;
   Elsif w_menu.sigla = 'SRSOLCEL' Then
      -- Trata envio de solicitações de celular
      If w_tramiteNovo.sigla = 'PP' Then
         -- Se o trâmite for de pendência na entrega de 
         -- acessórios de celular e não houver pendência, 
         -- pula para o próximo.
         select count(*) into w_cont
           from siw_solicitacao                       k
                inner     join sr_solicitacao_celular l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao)
          where l.pendencia          = 'S'
            and k.sq_siw_solicitacao = p_solic;
                 
         If w_cont = 0 Then
            select * into w_tramiteNovo
               from siw_tramite a
              where a.sq_menu = p_menu
                and a.ordem   = (w_tramiteNovo.ordem + 1);
         End If;
      Elsif w_tramiteNovo.sigla = 'EE' Then
         -- Se o trâmite for de conclusão, atualiza o campo PENDENCIA.
         update sr_solicitacao_celular 
            set pendencia        = 'N', 
            acessorios_pendentes = null
         where sq_siw_solicitacao = p_solic;
      End If;
   End If;
      
   -- Recupera a próxima chave da tabela de log
   select sq_siw_solic_log.nextval into w_chave_log from dual;
           
   -- Grava na tabela de logs da solicitação
   Insert Into siw_solic_log 
      (sq_siw_solic_log, sq_siw_solicitacao, sq_pessoa, sq_siw_tramite, data,
       devolucao, 
       observacao
      )
   (Select 
       w_chave_log,      p_solic,            p_pessoa,  p_tramiteAtual, sysdate,
       case when w_tramiteAtual.ordem > w_tramiteNovo.ordem then 'S' else 'N' end,
       'Envio '||
       case when w_tramiteAtual.sq_siw_tramite <> w_tramiteNovo.sq_siw_tramite
            then 'da fase "'||w_tramiteAtual.nome||'" '||' para a fase "'||w_tramiteNovo.nome||'".'
            else 'sem alteração de fase.'
       end
      from dual
   );
 
   -- Atualiza a situação da demanda
   Update siw_solicitacao set
      sq_siw_tramite = w_tramiteNovo.sq_siw_tramite
   Where sq_siw_solicitacao = p_solic;

   -- Atualiza o executor e 
   -- garante que solicitação em andamento não tem data de conclusão
   Update siw_solicitacao 
      set conclusao = null, 
          executor  = p_destinatario 
   Where sq_siw_solicitacao = p_solic;

   -- Atualiza o responsável atual pela demanda
   If w_tramiteNovo.ordem = 1 Then
      Update siw_solicitacao 
         set cadastrador = p_destinatario 
      Where sq_siw_solicitacao = p_solic;
   End If;

   -- Grava logs específicos de cada serviço/módulo
   If w_modulo.sigla = 'AC' Then
      -- Recupera a nova chave da tabela de encaminhamentos da demanda
      select sq_acordo_log.nextval into w_chave_dem from dual;
     
      -- Insere registro na tabela de encaminhamentos da demanda
      Insert into ac_acordo_log 
         (sq_acordo_log, sq_siw_solicitacao, cadastrador, destinatario,
          data_inclusao, observacao,         despacho,    sq_siw_solic_log,
          sq_tipo_log
         )
      Values (
          w_chave_dem,   p_solic,            p_pessoa,    p_destinatario,
          sysdate,       p_observacao,       p_despacho,  w_chave_log,
          p_tipo_log
         );
   End If;
end SP_PutNovoTramite;
/
