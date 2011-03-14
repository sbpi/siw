create or replace procedure sp_putDocumentoAssunto
   (p_operacao            in  varchar2,
    p_usuario             in number   default null,
    p_chave               in number   default null,
    p_assunto             in number   default null,
    p_principal           in varchar2 default null
   ) is
   w_cont          number(18);
   w_sq_caixa      pa_caixa.sq_caixa%type;
   w_dados_caixa   varchar2(4000);
   w_limite        varchar2(255);
   w_intermediario varchar2(255);
   w_final         varchar2(255);
   w_assunto       varchar2(1000);
   w_descricao     varchar2(1000);
   w_texto         varchar2(1000);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere apenas se o assunto já não estiver ligado ao documento
      select count(sq_assunto) into w_cont from pa_documento_assunto where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
      
      If w_cont = 0 Then
         -- Insere assunto na tabela de assuntos do documento
         insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (p_chave, p_assunto, p_principal);
      End If;

      If p_usuario is not null Then
         -- Registra os dados da autuação
         Insert Into siw_solic_log 
             (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
              sq_siw_tramite,            data,               devolucao, 
              observacao
             )
         (Select 
              sq_siw_solic_log.nextval,  p_chave,            p_usuario,
              a.sq_siw_tramite,          sysdate,           'N',
              'Indicação do assunto: '||b.codigo
             from siw_solicitacao a,
                  pa_assunto      b
            where a.sq_siw_solicitacao = p_chave
              and b.sq_assunto = p_assunto
         );
      End If;

      -- Verifica se o protocolo está em uma caixa
      select sq_caixa into w_sq_caixa from pa_documento where sq_siw_solicitacao = p_chave;
      
      If w_sq_caixa is not null Then
         w_cont := 0;
         -- Se estiver em uma caixa, atualiza os dados dela
         select retornaLimiteCaixa(w_sq_caixa)||'|@|' into w_dados_caixa from dual;
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
         where sq_caixa = w_sq_caixa;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de assuntos do documento
      update pa_documento_assunto set principal = p_principal where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      delete pa_documento_assunto where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
   End If;
end sp_putDocumentoAssunto;
/
