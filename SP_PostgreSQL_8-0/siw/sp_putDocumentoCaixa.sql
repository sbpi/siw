create or replace FUNCTION sp_putDocumentoCaixa
   (p_menu                 numeric,
    p_chave                numeric,
    p_usuario              numeric,
    p_caixa                numeric,
    p_pasta               varchar
   ) RETURNS VOID AS $$
DECLARE
   w_caixa_atual   numeric(18);
   w_dados_caixa   varchar(4000);
   w_limite        varchar(255);
   w_intermediario varchar(255);
   w_final         varchar(255);
   w_assunto       varchar(1000);
   w_descricao     varchar(1000);
   w_cont          numeric(10) := 0;
   w_texto         varchar(1000);
   
BEGIN
  -- Recupera a caixa atual para tratar alteração de caixa
  select sq_caixa into w_caixa_atual from pa_documento where sq_siw_solicitacao = p_chave;
  
  -- Atualiza a tabela de documentos
  update pa_documento 
    set sq_caixa      = p_caixa,
        pasta         = p_pasta
  where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave);
  
  -- Atualiza os dados do arquivamento da caixa
  select retornaLimiteCaixa(p_caixa)||'|@|' into w_dados_caixa;
  Loop
     w_cont := w_cont + 1;
     w_texto := substr(w_dados_caixa,1,instr(w_dados_caixa,'|@|')-1);
     If    w_cont = 1 Then w_limite        := w_texto;
     Elsif w_cont = 2 then w_intermediario := w_texto;
     Elsif w_cont = 3 then w_final         := w_texto;
     Elsif w_cont = 4 then w_assunto       := w_texto;
     Else                  w_descricao     := w_texto;
     End If;
     If w_cont > 4 Then Exit; End If;
     w_dados_caixa := substr(w_dados_caixa,instr(w_dados_caixa,'|@|')+3);
  End Loop;
  update pa_caixa
     set assunto             = substr(w_assunto,1,800),
         descricao           = substr(w_descricao,1,2000),
         data_limite         = w_limite,
         intermediario       = substr(w_intermediario,1,400),
         destinacao_final    = substr(w_final,1,40)
  where sq_caixa = p_caixa;

  If w_caixa_atual is not null and w_caixa_atual <> p_caixa Then
     -- Atualiza os dados do arquivamento da caixa em que o docuumento estava armazenado
     select retornaLimiteCaixa(w_caixa_atual)||'|@|' into w_dados_caixa;
     Loop
        w_cont := w_cont + 1;
        w_texto := substr(w_dados_caixa,1,instr(w_dados_caixa,'|@|')-1);
        If    w_cont = 1 Then w_limite        := w_texto;
        Elsif w_cont = 2 then w_intermediario := w_texto;
        Else                  w_final         := w_texto;
        End If;
        If w_cont > 2 Then Exit; End If;
        w_dados_caixa := substr(w_dados_caixa,instr(w_dados_caixa,'|@|')+3);
     End Loop;
     update pa_caixa
        set data_limite         = w_limite,
            intermediario       = w_intermediario,
            destinacao_final    = w_final
     where sq_caixa = p_caixa;
  End If;
  
  -- Registra os dados da autuação
  Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
  (Select 
        nextVal('sq_siw_solic_log'),  p_chave,            p_usuario,
        a.sq_siw_tramite,          now(),            'N',
        'Preparação para envio ao Arquivo Central. Caixa: '||c.numero||'/'||d.sigla||', Pasta: '||p_pasta||'.'
       from siw_solicitacao         a
            inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao),
            pa_caixa                c
            inner join eo_unidade   d on (c.sq_unidade         = d.sq_unidade)
      where (a.sq_siw_solicitacao = p_chave or a.sq_solic_pai = p_chave)
        and c.sq_caixa           = p_caixa
  );END; $$ LANGUAGE 'PLPGSQL' VOLATILE;