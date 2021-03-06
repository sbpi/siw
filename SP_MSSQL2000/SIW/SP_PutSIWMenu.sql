alter  procedure Sp_PutSIWMenu
   (@operacao            varchar(1),
    @chave               int   = null,
    @cliente             int,
    @nome                varchar(40),
    @acesso_geral        varchar(1),
    @sq_modulo              int,
    @tramite             varchar(1),
    @ultimo_nivel        varchar(1),
    @descentralizado     varchar(1),
    @externo             varchar(1),
    @ativo               varchar(1),
    @ordem               int,
    @sq_menu_pai         int   = null,
    @link                varchar(60) = null,
    @p1                  int   = null,
    @p2                  int   = null,
    @p3                  int   = null,
    @p4                  int   = null,
    @sigla               varchar(10) = null,
    @imagem              varchar(60) = null,
    @target              varchar(15) = null,
    @sq_unidade_exec     int   = null,
    @emite_os            varchar(1) = null,
    @consulta_opiniao    varchar(1) = null,
    @envia_email         varchar(1) = null,
    @exibe_relatorio     varchar(1) = null,
    @como_funciona       varchar(4000) = null,
    @vinculacao          varchar(1) = null,
    @data_hora           varchar(1) = null,
    @envia_dia_util      varchar(1) = null,
    @descricao      varchar(1) = null,
    @justificativa  varchar(1) = null,
    @finalidade          varchar(200) = null,
    @envio               varchar(1) = null,
    @controla_ano        varchar(1) = null,
    @libera_edicao       varchar(1) = null,
    @numeracao           int = null,
    @numerador           int = null,
    @sequencial          int = null,
    @ano_corrente        int = null,
    @prefixo             varchar(10) = null,
    @sufixo              varchar(10) = null
   ) as
   declare @w_chave        int;
   declare @w_sequencial   int; 
   set @w_sequencial = coalesce(@sequencial,0);
begin
   If @operacao = 'I' begin
      -- Recupera a próxima chave
     -- select sq_menu.nextval into @w_Chave from dual;
      
      -- Insere registro em siw_menu
      insert into siw_menu ( sq_menu_pai, link, p1, p2, p3, p4, sigla, imagem, target, 
         emite_os, consulta_opiniao, envia_email, exibe_relatorio, como_funciona, vinculacao,  
         data_hora, envia_dia_util, descricao, justificativa, finalidade,  
         sq_pessoa, nome, acesso_geral, sq_modulo, sq_unid_executora,
         tramite, ultimo_nivel, descentralizado, externo, ativo, ordem, destinatario,
         controla_ano, libera_edicao, numeracao_automatica, servico_numerador, sequencial, ano_corrente,
         prefixo, sufixo)
      values ( @sq_menu_pai, @link,
         @p1, @p2, @p3, @p4, upper(ltrim(rtrim(@sigla))), ltrim(rtrim(@imagem)), ltrim(rtrim(@target)),
         @emite_os, @consulta_opiniao, @envia_email, @exibe_relatorio, ltrim(rtrim(@como_funciona)), @vinculacao,
         @data_hora, @envia_dia_util, @descricao, @justificativa, @finalidade,
         @cliente, @nome, @acesso_geral, @sq_modulo, @sq_unidade_exec,
         @tramite, @ultimo_nivel, @descentralizado, @externo, @ativo, @ordem, coalesce(@envio,'S'),
         coalesce(@controla_ano,'N'), @libera_edicao, @numeracao, @numerador, @sequencial, @ano_corrente,
         @prefixo, @sufixo);
	set @w_chave = @@IDENTITY;
      -- Cria a opção do menu para todos os endereços da organização
      insert into siw_menu_endereco (sq_menu, sq_pessoa_endereco) 
        (select @w_chave, sq_pessoa_endereco 
           from co_pessoa_endereco a, co_tipo_endereco b 
          where a.sq_tipo_endereco = b.sq_tipo_endereco 
            and b.internet         = 'N' 
            and b.email            = 'N' 
            and sq_pessoa          = @cliente
        );
        
      -- Insere registros de configuração de e-mail se for serviço
      insert into sg_pessoa_mail( sq_pessoa, sq_menu, alerta_diario, tramitacao, conclusao, responsabilidade)
      (select  a.sq_pessoa, c.sq_menu, 'S', 'S', 'S', 
              case when substring(c.sigla, 1,2) = 'PJ' then 'S' else 'N' end
         from sg_autenticacao        a 
              inner   join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa)
                inner join siw_menu  c on (b.sq_pessoa_pai = c.sq_pessoa and c.tramite = 'S') -- Somente se for serviço
        where 0   = (select count(*) from sg_pessoa_mail where sq_pessoa = a.sq_pessoa and sq_menu = c.sq_menu)
          and c.sq_menu = @w_chave
      );

      -- Se for herança de serviço, grava também os trâmites
      If @chave is not null begin
         insert into siw_tramite
           ( sq_menu, nome, ordem, sigla, descricao, chefia_imediata, ativo, solicita_cc, envia_mail)
         (select @w_chave, nome, ordem, sigla, descricao, chefia_imediata, ativo, solicita_cc, envia_mail
            from siw_tramite
           where sq_menu = @chave
         );
      End 
      
   end else if @operacao = 'A' begin
      -- Se a opção do menu não tiver trâmite, apaga registro em siw_tramite
      If @tramite = 'N' begin
         delete siw_tramite where sq_menu = @chave;
      End 
      
      -- Se o serviço tiver numeração própria, evita que o sequencial atual seja retrocedido, para evitar problemas de duplicação
      if coalesce(@numeracao,0)=1 begin

         select @w_sequencial = coalesce(sequencial,0)  from siw_menu where sq_menu = @chave;

         If @w_sequencial < coalesce(@sequencial,0) begin set @w_sequencial = coalesce(@sequencial,0); End ;
      end 

      -- Altera registro
      update siw_menu set
          sq_menu_pai          = @sq_menu_pai,        link                 = @link,
          p1                   = @p1,                 p2                   = @p2, 
          p3                   = @p3,                 p4                   = @p4, 
          sigla                = upper(ltrim(rtrim(@sigla))), imagem               = ltrim(rtrim(@imagem)), 
          target               = ltrim(rtrim(@target)),       emite_os             = @emite_os, 
          consulta_opiniao     = @consulta_opiniao,   envia_email          = @envia_email, 
          exibe_relatorio      = @exibe_relatorio,    como_funciona        = ltrim(rtrim(@como_funciona)), 
          vinculacao           = @vinculacao,         data_hora            = @data_hora, 
          envia_dia_util       = @envia_dia_util,     descricao            = @descricao, 
          justificativa        = @justificativa, finalidade           = @finalidade,
          nome                 = @nome,               acesso_geral         = @acesso_geral, 
          sq_modulo            = @sq_modulo,             tramite              = @tramite, 
          ultimo_nivel         = @ultimo_nivel,       descentralizado      = @descentralizado, 
          externo              = @externo,            ordem                = @ordem,
          sq_unid_executora    = @sq_unidade_exec,    destinatario         = coalesce(@envio,'S'),
          controla_ano         = coalesce(@controla_ano,'N'),
          libera_edicao        = @libera_edicao,      numeracao_automatica = @numeracao,
          servico_numerador    = @numerador,          sequencial           = @w_sequencial,
          ano_corrente         = @ano_corrente,       prefixo              = @prefixo,
          sufixo               = @sufixo
      where sq_menu = @chave;
   end else if @operacao = 'E' begin
      -- Remove as configurações de e-mail do serviço
      delete sg_pessoa_mail where sq_menu = @chave;
      
      -- Remove as permissões de acesso por trâmite que os usuários têm
      delete sg_tramite_pessoa where sq_siw_tramite in (select sq_siw_tramite from siw_tramite where sq_menu = @chave);
      
      -- Remove as permissões de acesso por endereço que os usuários têm
      delete sg_pessoa_menu where sq_menu = @chave;
      
      -- Remove as permissões de acesso por endereço que os perfis têm
      delete sg_perfil_menu where sq_menu = @chave;
      
      -- Remove a opção dos endereços
      delete siw_menu_endereco where sq_menu = @chave;
      
      -- Remove os trâmites do serviço
      delete siw_tramite where sq_menu = @chave;
      
      -- Remove a opção do menu
      delete siw_menu where sq_menu = @chave;
   end else if @operacao = 'T' begin
      -- Ativa registro
      update siw_menu set ativo = 'S' where sq_menu = @chave;
   end else if @operacao = 'D' begin
      -- Desativa registro
      update siw_menu set ativo = 'N' where sq_menu = @chave;
   End 
   
end

