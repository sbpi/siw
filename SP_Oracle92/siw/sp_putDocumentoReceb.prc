create or replace procedure sp_putDocumentoReceb
   (p_operacao     in varchar2,
    p_pessoa       in number,
    p_unid_autua   in number   default null,
    p_nu_guia      in number   default null,
    p_ano_guia     in number   default null,
    p_observacao   in varchar2 default null
   ) is

   w_tramite siw_tramite%rowtype;

   cursor c_protocolo is
     select a.sq_documento_log, a.sq_siw_solicitacao, a.unidade_destino, a.unidade_origem, a.interno,
            d.sq_caixa
       from pa_documento_log          a
            inner join   pa_unidade   a1 on (a.unidade_origem     = a1.sq_unidade)
            inner   join pa_documento b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
              inner join pa_unidade   c  on (b.unidade_autuacao   = c.sq_unidade)
              left  join pa_caixa     d on  (b.sq_caixa           = d.sq_caixa)
      where p_nu_guia     = a.nu_guia
        and p_ano_guia    = a.ano_guia
        and coalesce(a1.sq_unidade_pai,a1.sq_unidade) = coalesce(c.sq_unidade_pai,c.sq_unidade)
        and a.recebimento is null;
begin
  for crec in c_protocolo loop
     If p_operacao = 'S' or p_operacao = 'U' Then -- Se o recebimento foi recusado
        -- Reverte envio em PA_DOCUMENTO
        update pa_documento a 
           set a.unidade_int_posse = crec.unidade_origem,
               a.sq_documento_pai  = null,
               a.pessoa_ext_posse  = null
         where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;

        -- Atualiza o log com os dados da recusa
        update pa_documento_log a 
           set a.recebedor   = p_pessoa, 
               a.recebimento = sysdate,
               a.resumo      = a.resumo||chr(13)||chr(10)||'*** RECUSADO'||case when p_observacao is null then '' else chr(13)||chr(10)||'Observação: '||p_observacao end
         where a.sq_documento_log = crec.sq_documento_log;
     Else
        -- Se tramitação interna, garante que a unidade de posse é a unidade recebedora
        update pa_documento a 
           set a.unidade_int_posse = case crec.interno when 'S' then crec.unidade_destino else crec.unidade_origem end
         where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
       
        -- Atualiza o log com os dados do recebimento
        update pa_documento_log a set a.recebedor = p_pessoa, a.recebimento = sysdate where a.sq_documento_log = crec.sq_documento_log;
       
        -- Atualiza a caixa, quando protocolo estiver arquivado
        update pa_caixa c
           set c.arquivo_data = sysdate
        where c.sq_caixa = coalesce(crec.sq_caixa,0);
        
        -- Se guia relativa a envio externo, coloca documentos na situação adequada
        If crec.interno = 'N' Then
           -- Recupera os dados do trâmite de envio para destino externo
           select b.* into w_tramite 
             from siw_solicitacao        a
                  inner join siw_tramite b on (b.sq_menu = b.sq_menu)
             where b.sigla              = 'DE'
               and a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
             
           -- Atualiza a tabela de solicitações
           Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite Where sq_siw_solicitacao = crec.sq_siw_solicitacao or sq_solic_pai = crec.sq_siw_solicitacao;
 
           -- Registra os dados do envio
           Insert Into siw_solic_log 
               (sq_siw_solic_log,          sq_siw_solicitacao,       sq_pessoa, 
                sq_siw_tramite,            data,                     devolucao, 
                observacao
               )
           (Select 
                sq_siw_solic_log.nextval,  crec.sq_siw_solicitacao,  p_pessoa,
                a.sq_siw_tramite,          sysdate,                  'N',
                'Envio externo: '||p_observacao
               from siw_solicitacao a
              where a.sq_siw_solicitacao = crec.sq_siw_solicitacao
                 or a.sq_solic_pai       = crec.sq_siw_solicitacao
           );
        End If;
     End If;
  end loop;
end sp_putDocumentoReceb;
/
