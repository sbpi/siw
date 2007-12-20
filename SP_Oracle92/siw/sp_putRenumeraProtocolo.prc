create or replace procedure SP_PutRenumeraProtocolo
   (p_usuario                  in  number,
    p_chave                    in  number,
    p_prefixo                  in  varchar2,
    p_numero                   in  number,
    p_ano                      in  varchar2
   ) is
   
   w_protocolo varchar2(30);
   w_codigo    varchar2(255);
   w_digito    varchar2(2);
   w_existe    number(18);
   
begin
   -- Verifica se o protocolo a ser renumerado existe
   select count(*) into w_existe from pa_documento a where a.sq_siw_solicitacao = p_chave;
   If w_existe > 0 Then

      -- Recupera o número atual do protocolo
      select a.prefixo||'.'||substr(1000000+a.numero_documento,2,6)||'/'||a.ano||'-'||substr(100+a.digito,2,2)
        into w_protocolo
        from pa_documento a
       where sq_siw_solicitacao = p_chave;
       
      -- Gera o novo número de protocolo
      w_codigo := p_prefixo||'.'||substr(1000000+p_numero,2,6)||'/'||p_ano;
      w_digito := validaCnpjCpf(w_codigo,'gerar');
      w_codigo := w_codigo||'-'||w_digito;

      -- Atualiza o novo número de protocolo
      update pa_documento a
         set a.prefixo          = p_prefixo,
             a.numero_documento = p_numero,
             a.ano              = p_ano,
             a.digito           = w_digito
       where sq_siw_solicitacao = p_chave;

      -- Insere log da renumeração
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao,    sq_pessoa, 
          sq_siw_tramite,            data,                  devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  a.sq_siw_solicitacao,  p_usuario,
          a.sq_siw_tramite,          sysdate,               'N',
          'RENUMERAÇÃO: '||w_protocolo||' PARA '||w_codigo
         from siw_solicitacao a
        where a.sq_siw_solicitacao = p_chave
      );
   End If;
end  SP_PutRenumeraProtocolo;
/
