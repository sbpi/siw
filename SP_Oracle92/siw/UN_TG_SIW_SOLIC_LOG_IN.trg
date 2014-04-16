create or replace trigger SIG.UN_TG_SIW_SOLIC_LOG_IN
  after insert on SIG.siw_solic_log
  for each row
/* Trigger específica para UNESCO
   Alimenta o portal de licitações quando uma licitação é enviada da fase de cadastramento para a fase de execução 
   ou quando uma anotação é feita.
*/
declare
  w_dispara      number(1);
  w_tipo         varchar2(255);
  w_chave        number(10);
  w_chave_aux    number(10);

  w_responsavel  co_pessoa.nome%type;
  w_em_resp      sg_autenticacao.email%type;
  w_solicitante  co_pessoa.nome%type;
  w_em_solic     sg_autenticacao.email%type;
  w_modalidade   lc_modalidade.nome%type;
  w_situacao     lc_situacao.codigo_externo%type;
  lic            cl_solicitacao%rowtype;
  sol            siw_solicitacao%rowtype;

  w_corpo        corporativo.un_mail.corpo%type;
  w_chtml        corporativo.un_mail.corpo_html%type := null;
  w_assunto      corporativo.un_mail.assunto%type;
begin
  -- Verifica se a trigger deve ser disparada
  select count(*) into w_dispara
    from siw_solicitacao a inner join siw_menu b on (a.sq_menu = b.sq_menu)
   where a.sq_siw_solicitacao = :new.sq_siw_solicitacao
     and b.sigla              = 'CLLCCAD' -- É licitação
     and lower(acentos(:new.observacao)) like 'anotacao:%';

  If w_dispara > 0 Then
     -- Registra o tipo de evento que disparou a trigger para configurar a mensagem
     w_tipo := substr(lower(acentos(:new.observacao)),1,5);

     -- Recupera os dados necessários ao envio de mensagem
     select * into sol from siw_solicitacao where sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     -- Recupera dados da tabela de licitação
     select * into lic from cl_solicitacao where sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     w_assunto := upper(sol.codigo_interno)||' - '||case w_tipo when 'envio' then 'ENVIO' else 'ANOTAÇÃO' end;

     -- Recupera dados para montagem do e-mail
     select solic.nome,    aut1.email, resp.nome,     aut2.email, mod.nome,     sit.nome
       into w_solicitante, w_em_solic, w_responsavel, w_em_resp, w_modalidade, w_situacao
       from co_pessoa solic, sg_autenticacao aut1, co_pessoa resp, sg_autenticacao aut2, 
            cl_solicitacao           cl
            inner join lc_modalidade mod on (cl.sq_lcmodalidade = mod.sq_lcmodalidade)
            left  join lc_situacao   sit on (cl.sq_lcsituacao   = sit.sq_lcsituacao)
      where solic.sq_pessoa       = sol.solicitante
        and aut1.sq_pessoa        = sol.solicitante
        and resp.sq_pessoa        = nvl(sol.executor,sol.cadastrador)
        and aut2.sq_pessoa        = nvl(sol.executor,sol.cadastrador)
        and cl.sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     w_corpo   := 'Prezado usuário(a),'||chr(13)||chr(10)||chr(13)||chr(10)||
                  'A '||upper(sol.codigo_interno)||' '||
                  case w_tipo
                       when 'envio' then 'foi gerada em '||to_char(:new.data,'dd/mm/yy, hh24:mi')||chr(13)||chr(10)
                       else              'tem novo posicionamento em '||to_char(:new.data,'dd/mm/yy, hh24:mi')||'.'||chr(13)||chr(10)||
                                         'Texto: '||substr(:new.observacao,instr(:new.observacao,chr(10))+1)||chr(13)||chr(10)
                  end||chr(13)||chr(10)||
                  'Detalhamento da licitação:'||chr(13)||chr(10)||chr(13)||chr(10)||
                  '  Objeto               : '||nvl(sol.descricao,'---')||chr(13)||chr(10)||
                  '  Responsável          : '||w_responsavel||chr(13)||chr(10)||
                  '  Modalidade           : '||w_modalidade||chr(13)||chr(10)||
                  '  Situação             : '||nvl(w_situacao,'---')||chr(13)||chr(10)||
                  '  Recebimento propostas: '||nvl(to_char(lic.data_abertura,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '  Abertura Envelope 1  : '||nvl(to_char(lic.envelope_1,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '  Abertura Envelope 2  : '||nvl(to_char(lic.envelope_2,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '  Abertura Envelope 3  : '||nvl(to_char(lic.envelope_3,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||chr(13)||chr(10)||
                  'Este e-mail foi gerado eletronicamente, favor não responder.';


     w_chtml   := '<HTML>'||chr(13)||chr(10)||
                  '<BODY style="background-color: #FFFFFF; font: 8pt Verdana, Arial, Helvetica, sans-serif;">'||chr(13)||chr(10)||
                  '<P>Prezado usu&aacute;rio(a),</P>'||chr(13)||chr(10)||
                  '<P>A '||upper(sol.codigo_interno)||' '||
                  case w_tipo
                       when 'envio' then 'foi gerada em '||to_char(:new.data,'dd/mm/yy, hh24:mi')||'.'||chr(13)||chr(10)
                       else              'tem novo posicionamento em '||to_char(:new.data,'dd/mm/yy, hh24:mi')||'.'||chr(13)||chr(10)||
                                         '<br>Texto: '||substr(:new.observacao,instr(:new.observacao,chr(10))+1)||chr(13)||chr(10)
                  end||'</P>'||chr(13)||chr(10)||
                  'Detalhamento da licitação:<table border=0 style="font: 8pt Verdana, Arial, Helvetica, sans-serif;">'||chr(13)||chr(10)||
                  '<tr><td>Objeto:<td>'||nvl(sol.descricao,'---')||chr(13)||chr(10)||
                  '<tr><td>Respons&aacute;vel:<td>'||w_responsavel||chr(13)||chr(10)||
                  '<tr><td>Modalidade:<td>'||w_modalidade||chr(13)||chr(10)||
                  '<tr><td>Situação:<td>'||nvl(w_situacao,'---')||chr(13)||chr(10)||
                  '<tr><td nowrap>Recebimento propostas:<td nowrap>'||nvl(to_char(lic.data_abertura,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '<tr><td nowrap>Abertura Envelope 1:<td nowrap>'||nvl(to_char(lic.envelope_1,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '<tr><td nowrap>Abertura Envelope 2:<td nowrap>'||nvl(to_char(lic.envelope_2,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '<tr><td nowrap>Abertura Envelope 3:<td nowrap>'||nvl(to_char(lic.envelope_3,'dd/mm/yy, hh24:mi'),'---')||chr(13)||chr(10)||
                  '</table><br>'||chr(13)||chr(10)||
                  '<P>Este e-mail foi gerado eletronicamente, favor n&atilde;o responder. </P>'||chr(13)||chr(10)||
                  '</BODY>'||chr(13)||chr(10)||
                  '</HTML>'||chr(13)||chr(10);

     -- Recupera o próximo valor da chave
     select nvl(max(handle)+1,0) into w_chave from corporativo.un_mail;
       
     -- Grava registro na tabela de e-mails
     insert into corporativo.un_mail
       (handle, z_grupo, corpo,   corpo_html, prioridade, datainclusao, dataenvio, enviado, assunto,   erro)
     values
       (w_chave, null,   w_corpo, w_chtml,    2,          sysdate,      null,      'N',     w_assunto, null);
    
     -- Recupera o próximo valor da chave da tabela
     select nvl(max(handle)+1,0) into w_chave_aux from corporativo.un_maildestinatario;
        
     -- Grava o registro do remetente da mensagem
     insert into corporativo.un_maildestinatario
       (handle,      z_grupo, mail,    nome,   endereco, tipo)
     values
       (corporativo.sq_maildest_handle.nextval, null,    w_chave, 'FABS WEB', 'servico.email@unesco.org.br',  1);
    
     -- Grava registros na tabela de destinatários
     for crec in (select w_responsavel nome, w_em_resp  endereco from dual
                  UNION
                  select w_solicitante nome, w_em_solic endereco from dual
                  UNION
                  select email nome, email endereco from eo_unidade where sq_unidade = sol.sq_unidade and email is not null
                  UNION
                  select 'licita@unesco.org.br' nome, 'licita@unesco.org.br' endereco from dual
                  UNION
                  select 'glicitacao@unesco.org.br' nome, 'glicitacao@unesco.org.br' endereco from dual
                 )
     Loop
         -- Recupera o próximo valor da chave
         select nvl(max(handle)+1,0) into w_chave_aux from corporativo.un_maildestinatario;
             
         -- Grava o registro
         insert into corporativo.un_maildestinatario
           (handle,      z_grupo, mail,    nome,   endereco, tipo)
         values
           (corporativo.sq_maildest_handle.nextval, null,    w_chave, crec.nome, crec.endereco,  2);
     End loop;
  End If;
end UN_TG_SIW_SOLIC_LOG_IN;
/
