create or replace FUNCTION SP_PutRenumeraProtocolo
   (p_usuario                   numeric,
    p_chave                     numeric,
    p_prefixo                   varchar,
    p_numero                    numeric,
    p_ano                       varchar
   ) RETURNS VOID AS $$
DECLARE
   
   w_protocolo varchar(30);
   w_codigo    varchar(255);
   w_digito    varchar(2);
   w_existe    numeric(18);
   
BEGIN
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
          a.sq_siw_tramite,          now(),               'N',
          'RENUMERAÇÃO: '||w_protocolo||' PARA '||w_codigo
         from siw_solicitacao a
        where a.sq_siw_solicitacao = p_chave
      );
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;