create or replace procedure SP_GetCustomerData
   (p_cliente  in  number,
    p_result   out sys_refcursor
   ) is
begin
   open p_result for 
      select a.sq_pessoa,              a.sq_cidade_padrao,      a.sq_agencia_padrao,     a.ativacao, 
             a.bloqueio,               a.desativacao,           a.tipo_autenticacao,     a.smtp_server, 
             a.siw_email_nome,         a.siw_email_conta,       a.siw_email_senha,       a.logo, 
             a.logo1,                  a.tamanho_min_senha,     a.tamanho_max_senha,     a.dias_vig_senha, 
             a.dias_aviso_expir,       a.maximo_tentativas,     a.fundo,                 a.upload_maximo, 
             a.envia_mail_tramite,     a.envia_mail_alerta,     a.georeferencia,         a.googlemaps_key, 
             a.ata_registro_preco,     a.ad_account_sufix,      a.ad_base_dn,            a.ad_domain_controlers, 
             a.ol_account_sufix,       a.ol_base_dn,            a.ol_domain_controlers,  a.syslog_server_name, 
             a.syslog_server_protocol, a.syslog_server_port,    a.syslog_facility,       a.syslog_fqdn, 
             a.syslog_timeout,         a.syslog_level_pass_ok,  a.syslog_level_pass_er,  a.syslog_level_sign_er, 
             a.syslog_level_write_ok,  a.syslog_level_write_er, a.syslog_level_res_er,
             b.co_uf,                  b.sq_pais,               b.sq_regiao,             b.nome as cidade, 
             c.codigo,                 c.nome as agencia,
             d.nome,                   d.nome_resumido,         d.sq_tipo_vinculo,
             e.cnpj,                   e.inscricao_estadual,    e.inicio_atividade,      e.sede,
             g.nome as pais,
             h.sq_segmento,            h.nome as segmento,      h.sigla as sg_segmento,
             i.sq_banco,               i.nome as banco
        from siw_cliente                     a
             inner   join co_cidade          b on (a.sq_cidade_padrao  = b.sq_cidade)
               inner join co_pais            g on (b.sq_pais           = g.sq_pais)
             inner   join co_agencia         c on (a.sq_agencia_padrao = c.sq_agencia)
               inner join co_banco           i on (c.sq_banco          = i.sq_banco)
             inner   join co_pessoa          d on (a.sq_pessoa         = d.sq_pessoa)
             inner   join co_pessoa_juridica e on (a.sq_pessoa         = e.sq_pessoa)
             inner   join co_pessoa_segmento f on (a.sq_pessoa         = f.sq_pessoa)
               inner join co_segmento        h on (f.sq_segmento       = h.sq_segmento)
       where a.sq_pessoa         = p_cliente;
end SP_GetCustomerData;
/
