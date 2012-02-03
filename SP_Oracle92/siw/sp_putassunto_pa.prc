create or replace procedure sp_PutAssunto_PA
   (p_operacao         in  varchar2             ,
    p_chave            in  number   default null,
    p_cliente          in  number   default null,
    p_chave_pai        in  number   default null,
    p_codigo           in  varchar2 default null,
    p_descricao        in  varchar2 default null,
    p_detalhamento     in  varchar2 default null,
    p_observacao       in  varchar2 default null,
    p_corrente_guarda  in  number   default null,
    p_corrente_anos    in  number   default null,
    p_intermed_guarda  in  number   default null,
    p_intermed_anos    in  number   default null,
    p_final_guarda     in  number   default null,
    p_final_anos       in  number   default null,
    p_destinacao_final in  number   default null,
    p_provisorio       in  varchar2 default null,
    p_ativo            in  varchar2 default null
   ) is

  w_cont          number(18);
  w_dados_caixa   varchar2(4000);
  w_limite        varchar2(255);
  w_intermediario varchar2(255);
  w_final         varchar2(255);
  w_assunto       varchar2(1000);
  w_descricao     varchar2(1000);
  w_texto         varchar2(1000);

  cursor c_caixas is
    select sq_caixa, retornaLimiteCaixa(sq_caixa)||'|@|' dados_caixa
      from (select distinct d.sq_caixa
              from pa_assunto                              a
                   inner       join pa_documento_assunto   b on (a.sq_assunto         = b.sq_assunto and
                                                                 b.principal          = 'S'
                                                                )
                     inner     join pa_documento           c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                       inner   join pa_caixa               d on (c.sq_caixa           = d.sq_caixa)
                       inner   join siw_solicitacao        e on (c.sq_siw_solicitacao = e.sq_siw_solicitacao)
             where a.sq_assunto = p_chave
               and e.sq_siw_tramite <> (select sq_siw_tramite from siw_tramite where sq_menu = e.sq_menu and sigla = 'CA')
           );

begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_assunto 
         (sq_assunto, cliente, sq_assunto_pai, codigo, descricao, detalhamento, observacao, fase_corrente_guarda,
          fase_corrente_anos, fase_intermed_guarda, fase_intermed_anos, fase_final_guarda,
          fase_final_anos, destinacao_final, provisorio, ativo)
      (select sq_assunto.nextval, p_cliente, p_chave_pai, p_codigo, p_descricao, p_detalhamento, p_observacao, p_corrente_guarda, 
              p_corrente_anos, p_intermed_guarda, p_intermed_anos, p_final_guarda, p_final_anos, 
              p_destinacao_final, p_provisorio, p_ativo 
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_assunto
         set sq_assunto_pai       = p_chave_pai,
             codigo               = p_codigo,
             descricao            = p_descricao,
             detalhamento         = p_detalhamento,
             observacao           = p_observacao,
             fase_corrente_guarda = p_corrente_guarda,
             fase_corrente_anos   = p_corrente_anos,
             fase_intermed_guarda = p_intermed_guarda,
             fase_intermed_anos   = p_intermed_anos,
             fase_final_guarda    = p_final_guarda,
             fase_final_anos      = p_final_anos,
             destinacao_final     = p_destinacao_final,
             provisorio           = p_provisorio,
             ativo                = p_ativo
       where sq_assunto = p_chave;

      For crec in c_caixas Loop
         w_cont := 0;
         -- Se estiver em uma caixa, atualiza os dados dela
         w_dados_caixa := crec.dados_caixa;
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
         where sq_caixa = crec.sq_caixa;
      End Loop;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_assunto where sq_assunto = p_chave;
   End If;
end sp_PutAssunto_PA;
/
