create or replace FUNCTION SP_PutSIWMenu
   (p_operacao             varchar,
    p_chave                numeric,
    p_cliente              numeric,
    p_nome                 varchar,
    p_acesso_geral         varchar,
    p_consulta_geral       varchar,
    p_modulo               numeric,
    p_tramite              varchar,
    p_ultimo_nivel         varchar,
    p_descentralizado      varchar,
    p_externo              varchar,
    p_ativo                varchar,
    p_ordem                numeric,
    p_sq_menu_pai          numeric,
    p_link                 varchar,
    p_p1                   numeric,
    p_p2                   numeric,
    p_p3                   numeric,
    p_p4                   numeric,
    p_sigla                varchar,
    p_imagem               varchar,
    p_target               varchar,
    p_sq_unidade_exec      numeric,
    p_emite_os             varchar,
    p_consulta_opiniao     varchar,
    p_envia_email          varchar,
    p_exibe_relatorio      varchar,
    p_como_funciona        varchar,
    p_vinculacao           varchar,
    p_data_hora            varchar,
    p_envia_dia_util       varchar,
    p_pede_descricao       varchar,
    p_pede_justificativa   varchar,
    p_finalidade           varchar,
    p_envio                varchar,
    p_controla_ano         varchar,
    p_libera_edicao        varchar,
    p_numeracao            numeric,
    p_numerador            numeric,
    p_sequencial           numeric,
    p_ano_corrente         numeric,
    p_prefixo              varchar,
    p_sufixo               varchar,
    p_envio_inclusao       varchar,
    p_cancela_sem_tramite  varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave        numeric(18);
   w_sequencial   numeric(18) := coalesce(p_sequencial,0);
BEGIN
   If p_operacao = 'I' Then
      -- Recupera a próxima chave
      select nextval('sq_menu') into w_Chave;
      
      -- Insere registro em SIW_MENU
      insert into siw_menu (sq_menu, sq_menu_pai, link, p1, p2, p3, p4, sigla, imagem, target, 
         emite_os, consulta_opiniao, envia_email, exibe_relatorio, como_funciona, vinculacao,  
         data_hora, envia_dia_util, descricao, justificativa, finalidade,  
         sq_pessoa, nome, acesso_geral, consulta_geral, sq_modulo, sq_unid_executora,
         tramite, ultimo_nivel, descentralizado, externo, ativo, ordem, destinatario,
         controla_ano, libera_edicao, numeracao_automatica, servico_numerador, sequencial, ano_corrente,
         prefixo, sufixo, envio_inclusao, cancela_sem_tramite)
      values (w_Chave, p_sq_menu_pai, p_link,
         p_p1, p_p2, p_p3, p_p4, upper(trim(p_sigla)), trim(p_imagem), trim(p_target),
         p_emite_os, p_consulta_opiniao, p_envia_email, p_exibe_relatorio, trim(p_como_funciona), p_vinculacao,
         p_data_hora, p_envia_dia_util, p_pede_descricao, p_pede_justificativa, p_finalidade,
         p_cliente, p_nome, p_acesso_geral, p_consulta_geral, p_modulo, p_sq_unidade_exec,
         p_tramite, p_ultimo_nivel, p_descentralizado, p_externo, p_ativo, p_ordem, coalesce(p_envio,'S'),
         coalesce(p_controla_ano,'N'), p_libera_edicao, p_numeracao, p_numerador, p_sequencial, p_ano_corrente,
         p_prefixo, p_sufixo, coalesce(p_envio_inclusao,'N'), coalesce(p_cancela_sem_tramite,'S'));
      
      -- Cria a opção do menu para todos os endereços da organização
      insert into siw_menu_endereco (sq_menu, sq_pessoa_endereco) 
        (select w_chave, sq_pessoa_endereco 
           from co_pessoa_endereco a, co_tipo_endereco b 
          where a.sq_tipo_endereco = b.sq_tipo_endereco 
            and b.internet         = 'N' 
            and b.email            = 'N' 
            and sq_pessoa          = p_cliente
        );
        
      -- Insere registros de configuração de e-mail se for serviço
      insert into sg_pessoa_mail(sq_pessoa_mail, sq_pessoa, sq_menu, alerta_diario, tramitacao, conclusao, responsabilidade)
      (select nextVal('sq_pessoa_mail'), a.sq_pessoa, c.sq_menu, 'S', 'S', 'S', 
              case when substr(c.sigla, 1,2) = 'PJ' then 'S' else 'N' end
         from sg_autenticacao        a 
              inner   join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa)
                inner join siw_menu  c on (b.sq_pessoa_pai = c.sq_pessoa and c.tramite = 'S') -- Somente se for serviço
        where 0   = (select count(*) from sg_pessoa_mail where sq_pessoa = a.sq_pessoa and sq_menu = c.sq_menu)
          and c.sq_menu = w_chave
      );

      -- Se for herança de serviço, grava também os trâmites
      If p_chave is not null Then
         insert into siw_tramite
           (sq_siw_tramite, sq_menu, nome, ordem, sigla, descricao, chefia_imediata, ativo, solicita_cc, envia_mail)
         (select nextVal('sq_siw_tramite'), w_chave, nome, ordem, sigla, descricao, chefia_imediata, ativo, solicita_cc, envia_mail
            from siw_tramite
           where sq_menu = p_chave
         );
      End If;
      
   Elsif p_operacao = 'A' Then
      -- Se a opção do menu não tiver trâmite, apaga registro em SIW_TRAMITE
      If p_tramite = 'N' Then
         DELETE FROM siw_tramite where sq_menu = p_chave;
      End If;
      
      -- Se o serviço tiver numeração própria, evita que o sequencial atual seja retrocedido, para evitar problemas de duplicação
      if coalesce(p_numeracao,0)=1 then
         select coalesce(sequencial,0) into w_sequencial from siw_menu where sq_menu = p_chave;
         If w_sequencial < coalesce(p_sequencial,0) Then w_sequencial := coalesce(p_sequencial,0); End If;
      end if;

      -- Altera registro
      update siw_menu set
          sq_menu_pai          = p_sq_menu_pai,        link                 = p_link,
          p1                   = p_p1,                 p2                   = p_p2, 
          p3                   = p_p3,                 p4                   = p_p4, 
          sigla                = upper(trim(p_sigla)), imagem               = trim(p_imagem), 
          target               = trim(p_target),       emite_os             = p_emite_os, 
          consulta_opiniao     = p_consulta_opiniao,   envia_email          = p_envia_email, 
          exibe_relatorio      = p_exibe_relatorio,    como_funciona        = trim(p_como_funciona), 
          vinculacao           = p_vinculacao,         data_hora            = p_data_hora, 
          envia_dia_util       = p_envia_dia_util,     descricao            = p_pede_descricao, 
          justificativa        = p_pede_justificativa, finalidade           = p_finalidade,
          nome                 = p_nome,               acesso_geral         = p_acesso_geral, 
          consulta_geral       = p_consulta_geral,
          sq_modulo            = p_modulo,             tramite              = p_tramite, 
          ultimo_nivel         = p_ultimo_nivel,       descentralizado      = p_descentralizado, 
          externo              = p_externo,            ordem                = p_ordem,
          sq_unid_executora    = p_sq_unidade_exec,    destinatario         = coalesce(p_envio,'S'),
          controla_ano         = coalesce(p_controla_ano,'N'),
          cancela_sem_tramite  = coalesce(p_cancela_sem_tramite,'N'),
          libera_edicao        = p_libera_edicao,      numeracao_automatica = p_numeracao,
          servico_numerador    = p_numerador,          sequencial           = w_sequencial,
          ano_corrente         = p_ano_corrente,       prefixo              = p_prefixo,
          sufixo               = p_sufixo,             envio_inclusao       = coalesce(p_envio_inclusao,'N')
      where sq_menu = p_chave;
   Elsif p_operacao = 'E' Then
      -- Remove as configurações de e-mail do serviço
      DELETE FROM sg_pessoa_mail where sq_menu = p_chave;
      
      -- Remove as permissões de acesso por trâmite que os usuários têm
      DELETE FROM sg_tramite_pessoa where sq_siw_tramite in (select sq_siw_tramite from siw_tramite where sq_menu = p_chave);
      
      -- Remove as permissões de acesso por endereço que os usuários têm
      DELETE FROM sg_pessoa_menu where sq_menu = p_chave;
      
      -- Remove as permissões de acesso por endereço que os perfis têm
      DELETE FROM sg_perfil_menu where sq_menu = p_chave;
      
      -- Remove a opção dos endereços
      DELETE FROM siw_menu_endereco where sq_menu = p_chave;
      
      -- Remove os trâmites do serviço
      DELETE FROM siw_tramite where sq_menu = p_chave;
      
      -- Remove a opção do menu
      DELETE FROM siw_menu where sq_menu = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update siw_menu set ativo = 'S' where sq_menu = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update siw_menu set ativo = 'N' where sq_menu = p_chave;
   End If;
   
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;
