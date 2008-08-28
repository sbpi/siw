alter function dbo.Acesso
  (@p_solicitacao int,
   @p_usuario     int,
   @p_tramite     int = null
  ) returns int as
/**********************************************************************************
* Nome      : Acesso
* Finalidade: Verificar se o usuário tem acesso a uma solicitacao, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  14/10/2003, 10:35
* Parâmetros:
*    p_solicitacao : chave primária de SIW_SOLICITACAO
*    p_usuario   : chave de acesso do usuário
* Retorno: campo do tipo bit
*   16: Se a solicitação deve aparecer na mesa de trabalho do usuário
*    8: Se o usuário é gestor do módulo à qual a solicitação pertence
*       Outra possibilidade é:
*          o usuário ser responsável por uma etapa de um projeto
*          o usuário ser titular ou substituto da unidade responsável por uma etapa de um projeto
*          o usuário ser responsável por alguma questão de um projeto (risco ou problema)
*    4: Se o usuário é o responsável pela unidade de lotação do solicitante da solicitação
*       Obs: somente se o trâmite for cumprido pela chefia imediata
*       Outra possibilidade é usuário cumprir algum trâmite no serviço
*    2: Se o usuário é o solicitante da solicitacao ou se é um interessado na sua execução
*    1: Se o usuário é o cadastrador ou executor da solicitação ou é está lotado na unidade de cadastramento
*       Outra possibilidade é:
*          o usuário ser representante do contrato
*          o usuário ser representante do projeto
*          a solicitação ser do módulo de planejamento estratégico
*    0: Se o usuário não tem acesso à solicitação
*    Se o usuário enquadrar-se em mais de uma das situações acima, o retorno será a
*    soma das situações. Assim,
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
*    16 a 31 - se o usuário deve cumprir o trâmite em que a solicitação está
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

 -- Verifica se a solicitação e o usuário informados existem
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
 
 -- Recupera as informações da opção à qual a solicitação pertence
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
        @w_unidade_resp         = coalesce(k1.sq_unidade, l1.sq_unidade,m1.sq_unidade,n1.sq_unidade,d.sq_unidade) --d.sq_unidade deve sempre ser a última opção
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
   
 -- Verifica se o usuário está ativo
 If @w_usuario_ativo = 'N' Begin
   -- Se não estiver, retorna 0
   Return(@Result)
 End
 -- Verifica se o usuário é o cadastrador
 If @p_usuario = @w_cadastrador Begin Set @Result = 1 End
 
 -- Verifica se o usuário é o executor
 If @p_usuario = @w_executor Begin Set @Result = 1 End
 
 -- Verifica se a solicitação é do módulo de planejamento estratégico
 If @w_sg_modulo = 'PE' and @w_interno = 'S' Begin Set @Result = 1; End

 -- Verifica se o usuário é representante de projeto
 select @w_existe = count(*) from pj_projeto_representante a where a.sq_pessoa = @p_usuario and a.sq_siw_solicitacao = @p_solicitacao;
 If @w_existe > 0 Begin Set @Result = 1; End
 
 -- Verifica se o usuário é representante de acordo
 select @w_existe = count(*) from ac_acordo_representante a where a.sq_pessoa = @p_usuario and a.sq_siw_solicitacao = @p_solicitacao;
 If @w_existe > 0 Begin Set @Result = 1; End
 
 -- Verifica se o usuário é o solicitante
 If @w_solicitante = @p_usuario Begin 
    Set @Result                 = @Result + 2 
    Set @w_unidade_beneficiario = @w_sq_unidade_lotacao
 End Else Begin 
    -- Verifica se o usuário participou de alguma forma na solicitação
    select @w_existe = 
           case when exists (
              -- Verifica se o usuário é interessado na demanda
              select 1 from gd_demanda_interes a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se já participou em algum momento na demanda 
              select 1 from gd_demanda_log a where a.sq_siw_solicitacao = @p_solicitacao and a.destinatario = @p_usuario
              UNION
              -- Verifica se o usuário é interessado no projeto
              select 1 from pj_projeto_interes a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se o usuário é interessado na solicitação
              select 1 from siw_solicitacao_interessado a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
              UNION
              -- Verifica se já participou em algum momento no projeto
              select 1 from pj_projeto_log a where a.sq_siw_solicitacao = @p_solicitacao and a.destinatario = @p_usuario
              UNION
              -- Verifica se é outra parte no acordo
              select 1 from ac_acordo a where a.sq_siw_solicitacao = @p_solicitacao and a.outra_parte= @p_usuario
              UNION
              -- Verifica se é outra parte no acordo
              select 1 from ac_acordo_outra_parte a where a.sq_siw_solicitacao = @p_solicitacao and a.outra_parte = @p_usuario
              UNION
              -- Verifica se é beneficiário de algum lançamento financeiro
              select 1 from fn_lancamento a where a.sq_siw_solicitacao = @p_solicitacao and a.pessoa = @p_usuario
              UNION
              -- Verifica se é proposto de alguma viagem
              select 1 from pd_missao a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
           )
           then 1
           else 0
           end
    If @w_existe > 0 Set @Result = @Result + 2 
    
    -- recupera o código e a lotação do solicitante, para verificar, mais abaixo,
    -- se o usuário é chefe dele
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
 
 -- Se o serviço for vinculado à unidade
 If @w_vinculacao = 'U'
    -- Verifica se o usuário está lotado ou se é titular/substituto 
    -- da unidade de CADASTRAMENTO da solicitação ou se é
    -- da unidade RESPONSÁVEL pelo projeto ou pela demanda
    If @w_sq_unidade_lotacao   = @w_unidade_solicitante or
       @w_sq_unidade_lotacao   = @w_unidade_resp Begin
       If @w_interno = 'S' Begin Set @Result = @Result + 1; End
    End Else If @w_sq_pessoa_titular    = @p_usuario or
          @w_sq_pessoa_substituto = @p_usuario
    Begin
       If @w_interno = 'S' Begin Set @Result = @Result + 4; End
    Else Begin
       -- Verifica se participa em algum trâmite do serviço
       select @w_existe = count(*) 
         from sg_tramite_pessoa a, siw_menu b, siw_tramite c
        where b.sq_menu             = c.sq_menu   
          and a.sq_siw_tramite      = c.sq_siw_tramite
          and IsNull(c.sigla,'---') <> 'CI'
          and b.sq_menu             = @w_sq_servico
          and a.sq_pessoa           = @p_usuario
       If @w_existe > 0 Set @Result = @Result + 4
       Else Begin
          -- Verifica se a unidade do usuário é uma das envolvidas na execução da demanda
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
          
             -- Verifica se a unidade do usuário é uma das envolvidas na execução do projeto
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
                -- Verifica se o usuário tem visão geral no centro de custos ao qual a solicitação está vinculada
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
 -- Caso contrário, se o serviço for vinculado à pessoa
 End Else Begin
    -- Verifica se o usuário é responsável pela unidade do solicitante
    select @w_chefe_beneficiario = count(*)
      from eo_unidade_resp a
     where a.sq_unidade = @w_unidade_beneficiario 
       and a.sq_pessoa  = @p_usuario
       and a.fim        is null

    -- Verifica se o usuário é o titular ou o substituto da unidade
    -- de lotação do BENEFICIÁRIO da solicitação, ou se participa em algum trâmite
    -- do serviço
    If @w_chefe_beneficiario > 0 Set @Result = @Result + 4 
    Else Begin
       -- Verifica se o usuário é responsável por uma unidade envolvida na execução
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
          -- Verifica se o usuário é responsável por uma unidade envolvida na execução
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
             -- Verifica se o usuário tem visão geral no centro de custos ao qual a solicitação está vinculada
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
 
 -- Verifica se o usuário é gestor do módulo à qual a solicitação pertence
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
       -- Se o trâmite da solicitação não for cadastramento inicial e se o trâmite não indicar destinatario
       -- e se não for gestor do sistema, complementa o resultado para somar 16
       Set @Result = @Result + 10;
    End
 Else Begin
    -- Verifica se é titular ou substituto de alguma unidade responsável por etapa
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
       -- Verifica se é responsável por alguma etapa do projeto ou por alguma questão ou por alguma meta
       select @w_existe = 
           case when exists (
                 -- Verifica se o usuário é responsável por alguma meta
                 select 1 from siw_solic_meta a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
                 UNION
                 -- Verifica se o usuário é responsável por alguma questão
                 select 1 from siw_restricao a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
                 UNION
                 -- Verifica se o usuário é responsável por alguma etapa
                 select 1 from pj_projeto_etapa a where a.sq_siw_solicitacao = @p_solicitacao and a.sq_pessoa = @p_usuario
           )
           then 1
           else 0
           end
       If @w_existe > 0 Set @Result = @Result + 8;
    End
 End

 -- Verifica se o usuário tem permissão para cumprir o trâmite atual da solicitação
 -- Uma das possibilidades é o trâmite ser cumprido pelo titular/substituto
 -- da unidade do cadastrador ou da solicitação
 If @w_chefia_imediata = 'S' Begin
 
    -- Se o serviço for vinculado à unidade, testa a unidade que cadastrou a solicitação.
    -- Caso contrário, testa a unidade de lotação do solicitante.
    If @w_vinculacao = 'U' Set @w_unidade_atual = @w_unidade_solicitante
    Else                   Set @w_unidade_atual = @w_unidade_beneficiario

 -- Outra possibilidade é o trâmite ser cumprido pelo titular/substituto
 -- da unidade de execução
 End Else If @w_chefia_imediata = 'U' Begin
    If @w_executor = @p_usuario Begin
       -- Se a solicitação tem indicação do executor, verifica se ele é o usuário.
       Set @Result = @Result + 16;
    End Else Begin
       -- Verifica se o usuário é responsável pela unidade executora
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
    -- Quando o trâmite for cumprido por todos os usuários internos
    If @w_interno = 'S' Set @Result = @Result + 16;
End Else If @w_sigla_situacao = 'AT' and  @w_solicitante = @p_Usuario and @w_consulta_opiniao = 'S' and @w_opiniao_solicitante is null Begin
    -- Outra possibilidade é a solicitação estar concluída e pendente de opinião pelo
    -- solicitante
    Set @Result = @Result + 16
End Else Begin
    -- Outra possibilidade é o trâmite ser cumprido por uma pessoa que tenha
    -- permissão para isso
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
       -- Outra possibilidade é a solicitação estar sendo executada pelo usuário
       -- Neste caso a solicitação deve estar em tramite ativo e diferente de cadastramento
       If @w_executor = @p_usuario and @w_ativo = 'S' and @w_sigla_situacao <> 'CI' Begin
          Set @Result = @Result + 16 
       End Else If @w_sg_modulo = 'OR' Begin
          -- Se for módulo de orçamento, outra possibilidade é a solicitação ter metas e o usuário ser:
          -- responsável pelo monitoramento, tit/subst do setor responsável pelo monitoramento ou
          -- tit/subst da unidade executora do serviço.
          If @p_usuario = @w_solicitante Set @Result = @Result + 16
          Else Begin
             -- Verifica se o usuário é responsável pela unidade executora
             select @w_existe = count(*)
               from eo_unidade_resp a
              where a.sq_unidade = @w_sq_unidade_executora
                and a.sq_pessoa  = @p_usuario
                and a.fim        is null;
             If @w_existe > 0 Set @Result = @Result + 16 
             Else Begin
                -- Verifica, nas demandas, se o usuário é responsável pela unidade responsável pelo monitoramento
                select @w_existe = count(*)
                  from eo_unidade_resp       a
                       inner join gd_demanda b on (a.sq_unidade = b.sq_unidade_resp)
                 where b.sq_siw_solicitacao = @p_solicitacao
                   and a.sq_pessoa          = @p_usuario
                   and a.fim                is null;
                If @w_existe > 0 Set @Result = @Result + 16 
                Else Beginr
                   -- Verifica, nas demandas, se o usuário é responsável pela unidade responsável pelo monitoramento
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
