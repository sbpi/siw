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
            b2.sigla as sg_tramite_atual,
            b3.sq_siw_tramite as sq_as,
            d.sq_caixa,
            case when e.cliente is not null then 'S' else 'N' end as atualiza_caixa
       from pa_documento_log             a
            inner   join   pa_unidade    a1 on (a.unidade_origem     = a1.sq_unidade)
            inner   join pa_documento    b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
              inner join pa_unidade      c  on (b.unidade_autuacao   = c.sq_unidade)
              left  join pa_caixa        d  on  (b.sq_caixa          = d.sq_caixa)
            left    join pa_parametro    e  on (a.sq_tipo_despacho   = e.despacho_arqcentral)
            inner   join siw_solicitacao b1 on (a.sq_siw_solicitacao = b1.sq_siw_solicitacao)
              inner join siw_tramite     b2 on (b1.sq_siw_tramite    = b2.sq_siw_tramite)
              inner join siw_tramite     b3 on (b2.sq_menu           = b3.sq_menu and b3.sigla = 'AS')
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
               a.pessoa_ext_posse  = null
         where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;

        -- Atualiza a caixa com os dados da recusa        
        If crec.sq_caixa is not null Then
           update pa_caixa
             set arquivo_guia_numero = null,
                 arquivo_guia_ano = null
           where sq_caixa = crec.sq_caixa;
        End If;
        
        -- Atualiza o log com os dados da recusa
        update pa_documento_log a 
           set a.recebedor   = p_pessoa,
               a.recebimento = sysdate,
               a.resumo      = a.resumo||chr(13)||chr(10)||'*** RECUSADO'||case when p_observacao is null then '' else chr(13)||chr(10)||'Observa��o: '||p_observacao end
         where a.sq_documento_log = crec.sq_documento_log;

        -- Se recusa de caixa pelo arquivo central, atualiza a situa��o dos protocolos para ARQUIVADO SETORIAL
        If crec.sg_tramite_atual = 'AT' Then
          update siw_solicitacao a 
             set a.sq_siw_tramite = crec.sq_as
           where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
        End If;
     Else
        -- Se tramita��o interna, garante que a unidade de posse � a unidade recebedora
        update pa_documento a 
           set a.unidade_int_posse = case crec.interno when 'S' then crec.unidade_destino else crec.unidade_origem end
         where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
       
        -- Atualiza o log com os dados do recebimento
        update pa_documento_log a set a.recebedor = p_pessoa, a.recebimento = sysdate where a.sq_documento_log = crec.sq_documento_log;
       
        -- Atualiza a caixa, quando o despacho for para arquivamento central
        If crec.atualiza_caixa = 'S' Then
           update pa_caixa c
              set c.arquivo_data = sysdate
           where c.sq_caixa = coalesce(crec.sq_caixa,0);
        End If;
        
        -- Se guia relativa a envio externo, coloca documentos na situa��o adequada
        If crec.interno = 'N' Then
           -- Recupera os dados do tr�mite de envio para destino externo
           select b.* into w_tramite 
             from siw_solicitacao         a
                  inner join siw_tramite  b on (a.sq_menu = b.sq_menu)
                  inner join pa_documento c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
             where b.sigla              = 'DE'
               and a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
             
           -- Atualiza a tabela de solicita��es
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
