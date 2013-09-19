create or replace procedure sp_putSolicDados
   (p_operacao            in varchar2,
    p_cliente             in number,
    p_chave               in number,
    p_usuario             in number,
    p_pessoa              in number,
    p_externo             in varchar2,
    p_observacao          in varchar2 default null
   ) is
   
   w_menu   siw_menu%rowtype;
   w_solic  siw_solicitacao%rowtype;
   w_modulo siw_modulo%rowtype;
begin
   -- Recupera os dados da solicitação
   select * into w_solic from siw_solicitacao where sq_siw_solicitacao = p_chave;
   
   -- Recupera os dados do servico
   select * into w_menu from siw_menu where sq_menu = w_solic.sq_menu;
   
   -- Recupera os dados do módulo
   select * into w_modulo from siw_modulo where sq_modulo = w_menu.sq_modulo;
   
   If w_modulo.sigla in ('GP','SR','CO','AL') Then -- Se for o módulo de pessoa, recursos logísticos, almoxarifado ou de compras
      If coalesce(w_solic.codigo_externo, '|@|') <> coalesce(p_externo, '|@|') or w_solic.cadastrador <> p_pessoa Then
         -- Registra a alteração do código externo
         If coalesce(w_solic.codigo_externo, '|@|') <> coalesce(p_externo, '|@|') Then
            Insert Into siw_solic_log 
                   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, sq_siw_tramite,         data,    devolucao, observacao)
            values (sq_siw_solic_log.nextval,  p_chave,            p_usuario, w_solic.sq_siw_tramite, sysdate, 'N',
                    'Alteração do código externo para: '||coalesce(p_externo,'nulo')||
                    case when p_observacao is null then '' else chr(13)||chr(10)||'Observação: '||p_observacao end
                   );
         End If;
 
         -- Registra a alteração do cadastrador
         If w_solic.cadastrador <> p_pessoa Then
            Insert Into siw_solic_log 
                   (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, sq_siw_tramite,         data,    devolucao, observacao)
            (Select sq_siw_solic_log.nextval,  p_chave,            p_usuario, w_solic.sq_siw_tramite, sysdate, 'N',
                    'Alteração do responsável pelo cadastramento para: '||a.nome||
                    case when p_observacao is null then '' else chr(13)||chr(10)||'Observação: '||p_observacao end
               from co_pessoa a
              where a.sq_pessoa = p_pessoa
            );
         End If;
      Elsif p_observacao is not null Then
         Insert Into siw_solic_log 
                (sq_siw_solic_log,         sq_siw_solicitacao, sq_pessoa, sq_siw_tramite,         data,    devolucao, observacao)
         values (sq_siw_solic_log.nextval, p_chave,            p_usuario, w_solic.sq_siw_tramite, sysdate, 'N',       'Observação: '||p_observacao);
      End If;
   Elsif w_modulo.sigla in ('GD','PD') Then
      If coalesce(w_solic.codigo_externo, '|@|') <> coalesce(p_externo, '|@|') or w_solic.cadastrador <> p_pessoa Then
         -- Registra a alteração do código externo
         If coalesce(w_solic.codigo_externo, '|@|') <> coalesce(p_externo, '|@|') Then
            Insert into gd_demanda_log 
                   (sq_demanda_log,            sq_siw_solicitacao, cadastrador, data_inclusao, observacao)
            values (sq_demanda_log.nextval,    p_chave,            p_usuario,   sysdate,
                    'Alteração do código externo para: '||coalesce(p_externo,'nulo')||
                    case when p_observacao is null then '' else chr(13)||chr(10)||'Observação: '||p_observacao end
                   );
         End If;
 
         -- Registra a alteração do cadastrador
         If w_solic.cadastrador <> p_pessoa Then
            Insert into gd_demanda_log 
                   (sq_demanda_log,            sq_siw_solicitacao, cadastrador, data_inclusao, observacao)
            (select sq_demanda_log.nextval,    p_chave,            p_usuario,   sysdate,
                    'Alteração do responsável pelo cadastramento para: '||a.nome||
                    case when p_observacao is null then '' else chr(13)||chr(10)||'Observação: '||p_observacao end
               from co_pessoa a
              where a.sq_pessoa = p_pessoa
            );
         End If;
      Elsif p_observacao is not null Then
         Insert Into siw_solic_log 
                (sq_siw_solic_log,         sq_siw_solicitacao, sq_pessoa, sq_siw_tramite,         data,    devolucao, observacao)
         values (sq_siw_solic_log.nextval, p_chave,            p_usuario, w_solic.sq_siw_tramite, sysdate, 'N',       'Observação: '||p_observacao);
      End If;
   End If;

   -- Atualiza o cadastrador e o código externo da solicitação
   update siw_solicitacao
     set cadastrador    = p_pessoa,
         codigo_externo = p_externo
   where sq_siw_solicitacao = p_chave;

end sp_putSolicDados;
/
