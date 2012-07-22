create or replace procedure sp_putDocumentoCaixa
   (p_menu                in  number,
    p_chave               in  number,
    p_usuario             in  number,
    p_caixa               in  number,
    p_pasta               in varchar2
   ) is
   w_caixa_atual   number(18);
   w_dados_caixa   varchar2(4000);
   w_limite        varchar2(255);
   w_intermediario varchar2(255);
   w_final         varchar2(255);
   w_assunto       varchar2(1000);
   w_descricao     varchar2(1000);
   w_cont          number(10) := 0;
   w_texto         varchar2(1000);
   
begin
  -- Recupera a caixa atual para tratar alteração de caixa
  select sq_caixa into w_caixa_atual from pa_documento where sq_siw_solicitacao = p_chave;
  
  -- Atualiza a tabela de documentos
  update pa_documento 
    set sq_caixa      = p_caixa,
        pasta         = p_pasta
  where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave);
  
  -- Atualiza os dados do arquivamento da caixa
  select retornaLimiteCaixa(coalesce(p_caixa,w_caixa_atual))||'|@|' into w_dados_caixa from dual;
  w_cont := 0;
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
     set assunto             = coalesce(substr(w_assunto,1,800),assunto),
         descricao           = coalesce(substr(w_descricao,1,2000),descricao),
         data_limite         = w_limite,
         intermediario       = substr(w_intermediario,1,400),
         destinacao_final    = substr(w_final,1,40)
  where sq_caixa = coalesce(p_caixa,w_caixa_atual);

  If w_caixa_atual is not null and w_caixa_atual <> coalesce(p_caixa,w_caixa_atual) Then
     -- Atualiza os dados do arquivamento da caixa em que o docuumento estava armazenado
     select retornaLimiteCaixa(w_caixa_atual)||'|@|' into w_dados_caixa from dual;
     w_cont := 0;
     Loop
        w_cont := w_cont + 1;
        w_texto := substr(w_dados_caixa,1,instr(w_dados_caixa,'|@|')-1);
        If    w_cont = 1 Then w_limite        := w_texto;
        Elsif w_cont = 2 then w_intermediario := w_texto;
        Elsif w_cont = 3 then w_final         := w_texto;
        Elsif w_cont = 4 then w_assunto       := w_texto;
        Else                  w_descricao     := w_texto;
        End If;
        If w_cont > 2 Then Exit; End If;
        w_dados_caixa := substr(w_dados_caixa,instr(w_dados_caixa,'|@|')+3);
     End Loop;
     update pa_caixa
        set assunto             = coalesce(substr(w_assunto,1,800),assunto),
            descricao           = coalesce(substr(w_descricao,1,2000),descricao),
            data_limite         = w_limite,
            intermediario       = substr(w_intermediario,1,400),
            destinacao_final    = substr(w_final,1,40)
     where sq_caixa = w_caixa_atual;
  End If;
  
  -- Registra os dados da autuação
  Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
  (Select sq_siw_solic_log.nextval,  p_chave,            p_usuario,
          a.sq_siw_tramite,          sysdate,            'N',
          case when p_caixa is null 
               then 'Remoção do protocolo da caixa '||c.numero||'/'||d.sigla 
               else 'Preparação para envio ao Arquivo Central. Caixa: '||c.numero||'/'||d.sigla
          end ||
          case when p_caixa is null then '.' else ', Pasta: '||p_pasta||'.' end
       from siw_solicitacao         a
            inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao),
            pa_caixa                c
            inner join eo_unidade   d on (c.sq_unidade         = d.sq_unidade)
      where (a.sq_siw_solicitacao = p_chave or a.sq_solic_pai = p_chave)
        and c.sq_caixa           = coalesce(p_caixa,w_caixa_atual)
  );
end sp_putDocumentoCaixa;
/
