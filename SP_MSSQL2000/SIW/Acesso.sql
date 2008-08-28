alter function dbo.Acesso
  (@p_solicitacao int,
   @p_usuario     int,
   @p_tramite     int = null
  ) returns int as
/**********************************************************************************
* Nome      : Acesso
* Finalidade: Verificar se o usu�rio tem acesso a uma solicitacao, de acordo com os par�metros informados
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  14/10/2003, 10:35
* Par�metros:
*    p_solicitacao : chave prim�ria de SIW_SOLICITACAO
*    p_usuario   : chave de acesso do usu�rio
* Retorno: campo do tipo bit
*   16: Se a solicita��o deve aparecer na mesa de trabalho do usu�rio
*    8: Se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
*       Outra possibilidade �:
*          o usu�rio ser respons�vel por uma etapa de um projeto
*          o usu�rio ser titular ou substituto da unidade respons�vel por uma etapa de um projeto
*          o usu�rio ser respons�vel por alguma quest�o de um projeto (risco ou problema)
*    4: Se o usu�rio � o respons�vel pela unidade de lota��o do solicitante da solicita��o
*       Obs: somente se o tr�mite for cumprido pela chefia imediata
*       Outra possibilidade � usu�rio cumprir algum tr�mite no servi�o
*    2: Se o usu�rio � o solicitante da solicitacao ou se � um interessado na sua execu��o
*    1: Se o usu�rio � o cadastrador ou executor da solicita��o ou � est� lotado na unidade de cadastramento
*       Outra possibilidade �:
*          o usu�rio ser representante do contrato
*          o usu�rio ser representante do projeto
*          a solicita��o ser do m�dulo de planejamento estrat�gico
*    0: Se o usu�rio n�o tem acesso � solicita��o
*    Se o usu�rio enquadrar-se em mais de uma das situa��es acima, o retorno ser� a
*    soma das situa��es. Assim,
*    3  - se for cadastrador e solicitante/interessado
*    5  - se for cadastrador e chefe da unidade
*    6  - se for solicitante e chefe da unidade
*    7  - se for cadastrador, solicitante e chefe da unidade
*    9  - se for cadastrador e gestor
*    10 - se for solicitante e gestor
*    11 - se for cadastrador, solicitante e gestor
*    12 - se for chefe da unidade e gestor
*    13 - se for cadastrador, chefe da unidade e gestor
*    14 - se for solicitante, chefe da unidade e gestor
*    15 - se for cadastrador, solicitante, chefe da unidade e gestor
*    16 a 31 - se o usu�rio deve cumprir o tr�mite em que a solicita��o est�
***********************************************************************************/
Begin
  Declare @w_interno                Varchar(1)
  Declare @w_sq_servico             Int
  Declare @w_acesso_geral           Varchar(1)
  Declare @w_modulo                 Int
  Declare @w_sg_modulo              Varchar(10)
  Declare @w_sigla                  Varchar(10)
  Declare @w_destinatario           Varchar(1)
  Declare @w_username               Int
  Declare @w_sq_unidade_lotacao     Int
  Declare @w_gestor_seguranca       Varchar(1)
  Declare @w_gestor_sistema         Varchar(1)
  Declare @w_sq_unidade_executora   Int
  Declare @w_consulta_opiniao       Varchar(1)
  Declare @w_envia_email            Varchar(1)
  Declare @w_exibe_relatorio        Varchar(1)
  Declare @w_vinculacao             Varchar(1)
  Declare @w_sq_siw_tramite         Int
  Declare @w_cadastrador            Int
  Declare @w_unidade_solicitante    Int
  Declare @w_sq_pessoa_executor     Int
  Declare @w_opiniao_solicitante    Int
  Declare @w_ordem                  Int
  Declare @w_sq_cc                  Int
  Declare @w_sigla_situacao         Varchar(10)
  Declare @w_ativo                  Varchar(1)
  Declare @w_usuario_ativo          Varchar(1)
  Declare @w_chefia_imediata        Varchar(1)
  Declare @w_sq_pessoa_titular      Int
  Declare @w_sq_pessoa_substituto   Int
  Declare @w_sq_endereco_unidade    Int
  Declare @w_solicitante            Int
  Declare @w_unidade_beneficiario   Int
  Declare @w_existe                 Int
  Declare @w_unidade_atual          Int
  Declare @w_chefe_beneficiario     Int
  Declare @w_executor               Int
  Declare @w_unidade_resp           Int
  Declare @Result                   Int
  Declare @p_unidade                Int

  Set @Result = 0

  declare c_unidade cursor for
     select pt.sq_unidade, a.sq_unidade_pai, coalesce(pt.sq_pessoa, -1) as sq_pessoa_titular,
            coalesce(ps.sq_pessoa, -1) as sq_pessoa_substituto
      from eo_unidade a
           left join (select b.sq_unidade, a.sq_pessoa, a.nome_resumido as nome
                       from co_pessoa                  a
                            inner join eo_unidade_resp b on (a.sq_pessoa       = b.sq_pessoa and
                                                             b.tipo_respons    = 'T' and
                                                             b.fim             is null and
                                                             b.sq_unidade      = @p_unidade
                                                            )
                     ) pt on (a.sq_unidade  = pt.sq_unidade)
           left join (select b.sq_unidade, a.sq_pessoa, nome_resumido as nome
                        from co_pessoa                  a
                             inner join eo_unidade_resp b on (a.sq_pessoa      = b.sq_pessoa and 
                                                              b.tipo_respons   = 'S' and 
                                                              b.fim            is null and 
                                                              b.sq_unidade     = @p_unidade 
                                                             )
                     ) ps on (a.sq_unidade  = ps.sq_unidade)
     where a.sq_unidade  = @p_unidade;

 -- Verifica se a solicita��o e o usu�rio informados existem
 select @w_existe = count(*) from siw_solicitacao where sq_siw_solicitacao = @p_solicitacao;
 If @w_existe = 0 Begin
    Set @Result = 0;
    Return (@Result);
 End
 
 select @w_existe = count(*) from co_pessoa where sq_pessoa = @p_usuario;
 If @w_existe = 0 Begin
    Set @Result = 0;
    Return (@Result);
 End
 
 -- Recupera as informa��es da op��o � qual a solicita��o pertence
 select @w_acesso_geral         = a.acesso_geral,         @w_sq_servico           = a.sq_menu, 
        @w_modulo               = a.sq_modulo,            @w_sigla                = a.sigla,
        @w_destinatario         = a.destinatario,
        @w_sg_modulo            = a1.sigla,
        @w_username             = b.sq_pessoa,            @w_sq_unidade_lotacao   = b.sq_unidade, 
        @w_gestor_seguranca     = b.gestor_seguranca,     @w_gestor_sistema       = b.gestor_sistema, 
        @w_usuario_ativo        = b.ativo,                @w_sq_unidade_executora = a.sq_unid_executora,
        @w_consulta_opiniao     = a.consulta_opiniao,     
        @w_envia_email          = a.envia_email,          @w_exibe_relatorio      = a.exibe_relatorio, 
        @w_vinculacao           = a.vinculacao,           @w_sq_siw_tramite       = d.sq_siw_tramite, 
        @w_solicitante          = d.solicitante,          @w_cadastrador          = d.cadastrador, 
        @w_unidade_solicitante  = d.sq_unidade,           @w_sq_pessoa_executor   = d.executor, 
        @w_opiniao_solicitante  = d.opiniao,              @w_ordem                = e.ordem, 
        @w_sq_cc                = case when d.sq_cc is not null 
                                       then d.sq_cc
                                       else case when i.sq_cc is not null
                                                 then i.sq_cc
                                                 else j.sq_cc
                                            end
                                  end,
        @w_sigla_situacao       = e.sigla,                @w_ativo                = e.ativo, 
        @w_chefia_imediata      = e.chefia_imediata,      @w_sq_pessoa_titular    = IsNull(f.sq_pessoa,-1), 
        @w_sq_pessoa_substituto = IsNull(g.sq_pessoa,-1), @w_sq_endereco_unidade  = h.sq_pessoa_endereco,
        @w_executor             = d.executor,
        @w_unidade_resp         = coalesce(k1.sq_unidade, l1.sq_unidade,m1.sq_unidade,n1.sq_unidade,d.sq_unidade) --d.sq_unidade deve sempre ser a �ltima op��o
   from sg_autenticacao                     b,
        siw_solicitacao                     d
        inner   join siw_menu               a  on (a.sq_menu                = d.sq_menu)
          inner join siw_modulo             a1 on (a.sq_modulo              = a1.sq_modulo)
        inner   join siw_tramite            e  on (d.sq_siw_tramite         = e.sq_siw_tramite)
        inner   join eo_unidade             h  on (d.sq_unidade             = h.sq_unidade)
        left    join eo_unidade_resp        f  on (d.sq_unidade             = f.sq_unidade and
                                                   f.tipo_respons           = 'T'          and
                                                   f.fim                    is null
                                                  )
        left    join eo_unidade_resp        g  on (d.sq_unidade             = g.sq_unidade and
                                                   g.tipo_respons           = 'T'          and
                                                   g.fim                    is null
                                                  )
        left    join siw_solicitacao        i  on (d.sq_solic_pai           = i.sq_siw_solicitacao)
          left  join siw_solicitacao        j  on (i.sq_solic_pai           = j.sq_siw_solicitacao)
        left    join pj_projeto             k  on (d.sq_siw_solicitacao     = k.sq_siw_solicitacao)
          left  join eo_unidade             k1 on (k.sq_unidade_resp        = k1.sq_unidade)
        left    join gd_demanda             l  on (d.sq_siw_solicitacao     = l.sq_siw_solicitacao)
          left  join eo_unidade             l1 on (l.sq_unidade_resp        = l1.sq_unidade)
        left    join pe_programa            m  on (d.sq_siw_solicitacao     = m.sq_siw_solicitacao)
          left  join eo_unidade             m1 on (m.sq_unidade_resp        = m1.sq_unidade)
        left    join cl_solicitacao         n  on (d.sq_siw_solicitacao     = n.sq_siw_solicitacao)
          left  join eo_unidade             n1 on (n.sq_unidade             = n1.sq_unidade)
  where d.sq_siw_solicitacao     = @p_solicitacao
    and b.sq_pessoa              = @p_usuario
  
 Set @Result = 0
 
 select @w_interno = b.interno
   from co_pessoa                  a
        inner join co_tipo_vinculo b on (a.sq_tipo_vinculo = b.sq_tipo_vinculo)
  where a.sq_pessoa = @p_usuario;
   
 -- Verifica se o usu�rio est� ativo
 If @w_usuario_ativo = 'N' Begin
   -- Se n�o estiver, retorna 0
   Return(@Result)
 End
 -- Verifica se o usu�rio � o cadastrador
 If @p_usuario = @w_cadastrador Begin Set @Result = 1 End
 
 -- Verifica se o usu�rio � o executor
 If @p_usuario = @w_executor Begin Set @Result = 1 End
 
 -- Verifica se a solicita��o � do m�dulo de planejamento estrat�gico
 If @w_sg_modulo = 'PE' and @w_interno = 'S' Begin Set @Result = 1; End

 -- Verifica se o usu�rio � representante de projeto
 select @w_existe = count(*) from pj_projeto_representante a where a.sq_pessoa = @p_usuario and a.sq_siw_solicitacao = @p_solicitacao;
 If @w_existe > 0 Begin Set @Result = 1; End
 
 -- Verifica se o usu�rio � representante de acordo
 select @w_existe = count(*) from ac_acordo_representante a where a.sq_pessoa = @p_usuario and a.sq_siw_solicitacao = @p_solicitacao;
 If @w_existe > 0 Begin Set @Result = 1; End
 
 -- Verifica se o usu�rio � o solicitante
 If @w_solicitante = @p_usuario Begin 
    Set @Result                 = @Result + 2 
    Set @w_unidade_beneficiario = @w_sq_unidade_lotacao
 End Else Begin 
    -- Verifica se o usu�rio participou de alguma forma na solicita��o
    select @w_existe = 
           case when exists (
              -- Verifica se o usu�rio � interessado na demanda
              select 1 from gd_demanda_interes a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se j� participou em algum momento na demanda 
              select 1 from gd_demanda_log a where a.sq_siw_solicitacao = @p_solicitacao and a.destinatario = @p_usuario
              UNION
              -- Verifica se o usu�rio � interessado no projeto
              select 1 from pj_projeto_interes a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se o usu�rio � interessado na solicita��o
              select 1 from siw_solicitacao_interessado a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se j� participou em algum momento no projeto
              select 1 from pj_projeto_log a where a.sq_siw_solicitacao = @p_solicitacao and a.destinatario = @p_usuario
              UNION
              -- Verifica se � outra parte no acordo
              select 1 from ac_acordo a where a.sq_siw_solicitacao = @p_solicitacao and a.outra_parte= @p_usuario
              UNION
              -- Verifica se � outra parte no acordo
              select 1 from ac_acordo_outra_parte a where a.sq_siw_solicitacao = @p_solicitacao and a.outra_parte = @p_usuario
              UNION
              -- Verifica se � benefici�rio de algum lan�amento financeiro
              select 1 from fn_lancamento a where a.sq_siw_solicitacao = @p_solicitacao and a.pessoa = @p_usuario
              UNION
              -- Verifica se � proposto de alguma viagem
              select 1 from pd_missao a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
           )
           then 1
           else 0
           end
    If @w_existe > 0 Set @Result = @Result + 2 
    
    -- recupera o c�digo e a lota��o do solicitante, para verificar, mais abaixo,
    -- se o usu�rio � chefe dele
    select @w_solicitante = a.solicitante, @w_unidade_beneficiario = b.sq_unidade
      from siw_solicitacao            a
           inner join sg_autenticacao b on (a.solicitante = b.sq_pessoa)
     where a.sq_siw_solicitacao = @p_solicitacao;

    if @w_existe > 0 begin
       select @w_solicitante = a.solicitante, @w_unidade_beneficiario = b.sq_unidade
         from siw_solicitacao            a
              inner join sg_autenticacao b on (a.solicitante = b.sq_pessoa)
        where a.sq_siw_solicitacao = @p_solicitacao;
    end else begin
       select @w_solicitante = a.solicitante, @w_unidade_beneficiario = b.sq_unidade
         from siw_solicitacao            a
              inner join sg_autenticacao b on (a.cadastrador = b.sq_pessoa)
        where a.sq_siw_solicitacao = @p_solicitacao;
    end
 End
 
 -- Se o servi�o for vinculado � unidade
 If @w_vinculacao = 'U'
    -- Verifica se o usu�rio est� lotado ou se � titular/substituto 
    -- da unidade de CADASTRAMENTO da solicita��o ou se �
    -- da unidade RESPONS�VEL pelo projeto ou pela demanda
    If @w_sq_unidade_lotacao   = @w_unidade_solicitante or
       @w_sq_unidade_lotacao   = @w_unidade_resp Begin
       If @w_interno = 'S' Begin Set @Result = @Result + 1; End
    End Else If @w_sq_pessoa_titular    = @p_usuario or
          @w_sq_pessoa_substituto = @p_usuario
    Begin
       If @w_interno = 'S' Begin Set @Result = @Result + 4; End
    Else Begin
       -- Verifica se participa em algum tr�mite do servi�o
       select @w_existe = count(*) 
         from sg_tramite_pessoa a, siw_menu b, siw_tramite c
        where b.sq_menu             = c.sq_menu   
          and a.sq_siw_tramite      = c.sq_siw_tramite
          and IsNull(c.sigla,'---') <> 'CI'
          and b.sq_menu             = @w_sq_servico
          and a.sq_pessoa           = @p_usuario
       If @w_existe > 0 Set @Result = @Result + 4
       Else Begin
          -- Verifica se a unidade do usu�rio � uma das envolvidas na execu��o da demanda
          select @w_existe = count(*) 
            from gd_demanda_envolv a
                 inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                  b.sq_pessoa    = @p_usuario    and
                                                  b.fim          is null
                                                 )
           where a.sq_siw_solicitacao = @p_solicitacao
             and a.sq_unidade         = @w_sq_unidade_lotacao
          If @w_existe > 0 Set @Result = @Result + 4 
          Else Begin
          
             -- Verifica se a unidade do usu�rio � uma das envolvidas na execu��o do projeto
             select @w_existe = count(*) 
               from pj_projeto_envolv a
                    inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                     b.sq_pessoa    = @p_usuario    and
                                                     b.fim          is null
                                                    )
              where a.sq_siw_solicitacao = @p_solicitacao
                and a.sq_unidade         = @w_sq_unidade_lotacao
             If @w_existe > 0 Set @Result = @Result + 4
             Else Begin
                -- Verifica se o usu�rio tem vis�o geral no centro de custos ao qual a solicita��o est� vinculada
                select @w_existe = count(*) 
                  from siw_pessoa_cc a
                 where a.sq_pessoa = @p_usuario
                   and a.sq_menu   = @w_sq_servico
                   and a.sq_cc     = @w_sq_cc;
                If @w_existe > 0 Begin
                   If @w_interno = 'S' Begin
                      Set @Result = @Result + 4;
                   End Else Begin
                      Set @Result = @Result + 2;
                   End
                End
             End
          End
       End
    End
 -- Caso contr�rio, se o servi�o for vinculado � pessoa
 End Else Begin
    -- Verifica se o usu�rio � respons�vel pela unidade do solicitante
    select @w_chefe_beneficiario = count(*)
      from eo_unidade_resp a
     where a.sq_unidade = @w_unidade_beneficiario 
       and a.sq_pessoa  = @p_usuario
       and a.fim        is null

    -- Verifica se o usu�rio � o titular ou o substituto da unidade
    -- de lota��o do BENEFICI�RIO da solicita��o, ou se participa em algum tr�mite
    -- do servi�o
    If @w_chefe_beneficiario > 0 Set @Result = @Result + 4 
    Else Begin
       -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
       select @w_existe = count(*)
         from gd_demanda_envolv           a
               inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                b.sq_pessoa    = @p_usuario    and
                                                b.fim          is null
                                               )
        where a.sq_siw_solicitacao = @p_solicitacao
          and a.sq_unidade         = @w_unidade_beneficiario;
       If @w_existe > 0 Begin 
          Set @Result = @Result + 4; 
       End Else Begin
          -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
          select @w_existe = count(*)
             from pj_projeto_envolv          a
                  inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                   b.sq_pessoa    = @p_usuario    and
                                                   b.fim          is null
                                                  )
            where a.sq_siw_solicitacao = @p_solicitacao
              and a.sq_unidade         = @w_unidade_beneficiario;
          If @w_existe > 0 Begin 
             Set @Result = @Result + 4; 
          End Else Begin
             -- Verifica se o usu�rio tem vis�o geral no centro de custos ao qual a solicita��o est� vinculada
             select @w_existe = count(*)
               from siw_pessoa_cc a
              where a.sq_pessoa = @p_usuario
                and a.sq_menu   = @w_sq_servico
                and a.sq_cc     = @w_sq_cc;
             If @w_existe > 0 Begin
                If @w_interno = 'S' Set @Result = @Result + 4; Else Set @Result = @Result + 2;
             End
          End
       End
    End
 End
 
 -- Verifica se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
 select @w_existe = count(*)
   from sg_pessoa_modulo a
  where a.sq_pessoa          = @p_usuario
    and a.sq_modulo          = @w_modulo
    and (a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(@w_unidade_solicitante,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(@w_unidade_beneficiario,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(@w_unidade_resp,0))
        );
 If @w_existe > 0 or @w_gestor_sistema = 'S'
    Set @Result = @Result + 6
    If @w_existe > 0 and @w_destinatario = 'N' and @w_sigla_situacao <> 'CI' Begin
       -- Se o tr�mite da solicita��o n�o for cadastramento inicial e se o tr�mite n�o indicar destinatario
       -- e se n�o for gestor do sistema, complementa o resultado para somar 16
       Set @Result = @Result + 10;
    End
 Else Begin
    -- Verifica se � titular ou substituto de alguma unidade respons�vel por etapa
    select @w_existe = count(*)
      from pj_projeto_etapa           a
           inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                            b.sq_pessoa    = @p_usuario    and
                                            b.fim          is null
                                           )
     where a.sq_siw_solicitacao = @p_solicitacao
       and a.sq_unidade         = @w_unidade_beneficiario;
    If @w_existe > 0 Set @Result = @Result + 8; 
    Else Begin
       -- Verifica se � respons�vel por alguma etapa do projeto ou por alguma quest�o ou por alguma meta
       select @w_existe = 
           case when exists (
                 -- Verifica se o usu�rio � respons�vel por alguma meta
                 select 1 from siw_solic_meta a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
                 UNION
                 -- Verifica se o usu�rio � respons�vel por alguma quest�o
                 select 1 from siw_restricao a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
                 UNION
                 -- Verifica se o usu�rio � respons�vel por alguma etapa
                 select 1 from pj_projeto_etapa a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
           )
           then 1
           else 0
           end
       If @w_existe > 0 Set @Result = @Result + 8;
    End
 End

 -- Verifica se o usu�rio tem permiss�o para cumprir o tr�mite atual da solicita��o
 -- Uma das possibilidades � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade do cadastrador ou da solicita��o
 If @w_chefia_imediata = 'S' Begin
 
    -- Se o servi�o for vinculado � unidade, testa a unidade que cadastrou a solicita��o.
    -- Caso contr�rio, testa a unidade de lota��o do solicitante.
    If @w_vinculacao = 'U' Set @w_unidade_atual = @w_unidade_solicitante
    Else                   Set @w_unidade_atual = @w_unidade_beneficiario

 -- Outra possibilidade � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade de execu��o
 End Else If @w_chefia_imediata = 'U' Begin
    If @w_executor = @p_usuario Begin
       -- Se a solicita��o tem indica��o do executor, verifica se ele � o usu�rio.
       Set @Result = @Result + 16;
    End Else Begin
       -- Verifica se o usu�rio � respons�vel pela unidade executora
       select @w_existe = count(*)
         from eo_unidade_resp a
        where a.sq_unidade = @w_sq_unidade_executora
          and a.sq_pessoa  = @p_usuario
          and a.fim        is null
       If @w_existe > 0 Begin 
          Set @Result = @Result + 16
       End Else Begin
          select @w_existe = count(*)
            from sg_tramite_pessoa a 
           where a.sq_pessoa          = @p_usuario
             and a.sq_pessoa_endereco = @w_sq_endereco_unidade 
             and a.sq_siw_tramite     = @w_sq_siw_tramite
             and (@w_sg_modulo <> 'PA' or
                  (@w_sg_modulo = 'PA' and
                   0 < (select count(*) from pa_documento where sq_siw_solicitacao = @p_solicitacao and unidade_int_posse = @w_sq_unidade_lotacao)
                  )
                 );
          If @w_existe > 0 Begin Set @Result = @Result + 16 End
       End
    End
End Else If @w_chefia_imediata = 'I' Begin
    -- Quando o tr�mite for cumprido por todos os usu�rios internos
    If @w_interno = 'S' Set @Result = @Result + 16;
End Else If @w_sigla_situacao = 'AT' and  @w_solicitante = @p_Usuario and @w_consulta_opiniao = 'S' and @w_opiniao_solicitante is null Begin
    -- Outra possibilidade � a solicita��o estar conclu�da e pendente de opini�o pelo
    -- solicitante
    Set @Result = @Result + 16
End Else Begin
    -- Outra possibilidade � o tr�mite ser cumprido por uma pessoa que tenha
    -- permiss�o para isso
    select @w_existe = count(*)
      from sg_tramite_pessoa a 
     where a.sq_pessoa          = @p_usuario
       and a.sq_pessoa_endereco = @w_sq_endereco_unidade 
       and a.sq_siw_tramite     = @w_sq_siw_tramite
       and (@w_sg_modulo <> 'PA' or
            (@w_sg_modulo = 'PA' and
             0 < (select count(*) from pa_documento where sq_siw_solicitacao = @p_solicitacao and unidade_int_posse = @w_sq_unidade_lotacao)
            )
           );
    If @w_existe > 0 and @w_destinatario = 'N' Begin 
       Set @Result = @Result + 16 
    End Else Begin
       -- Outra possibilidade � a solicita��o estar sendo executada pelo usu�rio
       -- Neste caso a solicita��o deve estar em tramite ativo e diferente de cadastramento
       If @w_executor = @p_usuario and @w_ativo = 'S' and @w_sigla_situacao <> 'CI' Begin
          Set @Result = @Result + 16 
       End Else If @w_sg_modulo = 'OR' Begin
          -- Se for m�dulo de or�amento, outra possibilidade � a solicita��o ter metas e o usu�rio ser:
          -- respons�vel pelo monitoramento, tit/subst do setor respons�vel pelo monitoramento ou
          -- tit/subst da unidade executora do servi�o.
          If @p_usuario = @w_solicitante Set @Result = @Result + 16
          Else Begin
             -- Verifica se o usu�rio � respons�vel pela unidade executora
             select @w_existe = count(*)
               from eo_unidade_resp a
              where a.sq_unidade = @w_sq_unidade_executora
                and a.sq_pessoa  = @p_usuario
                and a.fim        is null;
             If @w_existe > 0 Set @Result = @Result + 16 
             Else Begin
                -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                select @w_existe = count(*)
                  from eo_unidade_resp       a
                       inner join gd_demanda b on (a.sq_unidade = b.sq_unidade_resp)
                 where b.sq_siw_solicitacao = @p_solicitacao
                   and a.sq_pessoa          = @p_usuario
                   and a.fim                is null;
                If @w_existe > 0 Set @Result = @Result + 16 
                Else Beginr
                   -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                   select @w_existe = count(*)
                     from eo_unidade_resp       a
                          inner join pj_projeto b on (a.sq_unidade = b.sq_unidade_resp)
                    where b.sq_siw_solicitacao = @p_solicitacao
                      and a.sq_pessoa          = @p_usuario
                      and a.fim                is null;
                   If @w_existe > 0 Set @Result = @Result + 16 
                End
             End
          End
       End
    End
 End

 return(@Result)
end
