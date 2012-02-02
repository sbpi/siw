create or replace procedure SP_PutLancamentoEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number,
    p_devolucao           in varchar2,
    p_observacao          in varchar2  default null,
    p_destinatario        in number    default null,
    p_despacho            in varchar2  default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_reg           number(18) := null;
   w_chave         number(18) := null;
   w_pp            number(18);
   w_novo_tramite  number(18) := p_novo_tramite;
   w_pendencia     number(18) := 0;
   w_chave_dem     number(18) := null;
   w_chave_arq     number(18) := null;
   w_menu          siw_menu%rowtype;

begin
   -- Recupera os dados da opção de menu
   select * into w_menu from siw_menu where sq_menu = p_menu;
   
   If p_tramite <> w_novo_tramite Then
      -- Verifica se há pendência na prestação de contas de alguma viagem
      select count(*) into w_pendencia
        from pd_missao                        a
             inner   join pd_categoria_diaria f on (a.diaria              = f.sq_categoria_diaria)
             inner   join siw_solicitacao     b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
               inner join siw_tramite         c on (b.sq_siw_tramite      = c.sq_siw_tramite and
                                                    c.sigla               in ('PC','AP')
                                                   )
               inner join siw_menu            d on (b.sq_menu             = d.sq_menu)
               inner join pd_parametro        e on (d.sq_pessoa           = e.cliente)
       where 0           > soma_dias(e.cliente,trunc(b.fim),f.dias_prestacao_contas + 1,'U') - trunc(sysdate)
         and 0           < (select count(*)
                              from siw_solicitacao        w
                                   inner join siw_tramite x on (w.sq_menu = x.sq_menu)
                             where w.sq_siw_solicitacao = p_chave
                               and x.sigla              = 'PP'
                            )
         and a.sq_pessoa = (select pessoa from fn_lancamento where sq_siw_solicitacao = p_chave)
         and w_menu.sigla = 'FNDVIA';

      -- Se houver, coloca o pagamento como pendente de prestação de contas
      If w_pendencia > 0 Then
         select c.sq_siw_tramite into w_novo_tramite 
           from siw_solicitacao        b
                inner join siw_tramite c on (b.sq_menu             = c.sq_menu and
                                             c.sigla               = 'PP'
                                            )
          where b.sq_siw_solicitacao = p_chave;
      End If;
      
      -- Recupera a próxima chave
      select sq_siw_solic_log.nextval into w_chave from dual;
      
      -- Se houve mudança de fase, grava o log
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (Select 
          w_chave,                   p_chave,            p_pessoa,
          p_tramite,                 sysdate,            p_devolucao,
          'Envio da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
         from siw_tramite a,
              siw_tramite b
        where a.sq_siw_tramite = p_tramite
          and b.sq_siw_tramite = w_novo_tramite
      );

      -- Atualiza a situação da solicitação
      Update siw_solicitacao set
         sq_siw_tramite = w_novo_tramite,
         conclusao      = null
      Where sq_siw_solicitacao = p_chave;

      -- Atualiza a situação do lançamento financeiro
      Update fn_lancamento set quitacao = null Where sq_siw_solicitacao = p_chave;
   End If;

   -- Verifica se o envio é na/para fase de cadastramento. Se for, atualiza o cadastrador.
   If p_destinatario is  not null Then

      -- Atualiza o responsável atual pela demanda
      Update siw_solicitacao set conclusao = null, executor = p_destinatario Where sq_siw_solicitacao = p_chave;

      select count(*) into w_reg from siw_tramite where sq_siw_tramite = Nvl(w_novo_tramite,p_tramite) and sigla='CI';
      If w_reg > 0 Then
         Update siw_solicitacao set cadastrador = p_destinatario Where sq_siw_solicitacao = p_chave;
      End If;
   End If;

   -- Recupera a nova chave da tabela de encaminhamentos da demanda
   select sq_lancamento_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de encaminhamentos da demanda
   Insert into fn_lancamento_log 
      (sq_lancamento_log,             sq_siw_solicitacao, cadastrador, 
       destinatario,              data_inclusao,      observacao, 
       despacho,                  sq_siw_solic_log
      )
   Values (
       w_chave_dem,               p_chave,            p_pessoa,
       p_destinatario,            sysdate,            p_observacao,
       p_despacho,                w_chave
    );

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Decide se o vínculo do arquivo será com o log da solicitação ou da demanda.
      If p_tramite <> w_novo_tramite Then
         -- Insere registro em SIW_SOLIC_LOG_ARQ
         insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
         values (w_chave, w_chave_arq);
      Else
         -- Insere registro em FN_LANCAMENTO_LOG_ARQ
         insert into fn_lancamento_log_arq (sq_lancamento_log, sq_siw_arquivo)
         values (w_chave_dem, w_chave_arq);
      End If;
   End If;
      
end SP_PutLancamentoEnvio;
/
