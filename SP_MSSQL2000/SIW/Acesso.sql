alter function dbo.Acesso
  (@p_solicitacao int,
   @p_usuario     int
  ) returns int as
/**********************************************************************************
* Nome      : SolicitacaoAcesso
* Finalidade: Verificar se o usuário têm acesso a uma solicitacao, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  14/10/2003, 10:35
* Parâmetros:
*    @p_solicitacao : chave primária de SR_SOLICITACAO
*    @p_usuario   : chave de acesso do usuário
* Retorno: campo do tipo bit
*   16: Se a solicitação deve aparecer na mesa de trabalho do usuário
*    8: Se o usuário é gestor do módulo à qual a solicitação pertence
*    4: Se o usuário é o responsável pela unidade de lotação do solicitante da solicitação
*       Obs: somente se o trâmite for cumprido pela chefia imediata
*       Outra possibilidade é se o usuário cumprir algum trâmite no serviço
*       Outra possibilidade é se o serviço for de interesse de toda a unidade e o usuário for lotado nela
*    2: Se o usuário é o solicitante da solicitacao ou se é um interessado na sua execução
*    1: Se o usuário é o cadastrador da solicitação
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
  Declare @w_sq_servico             Int
  Declare @w_acesso_geral           Varchar(1)
  Declare @w_modulo                 Int
  Declare @w_sigla                  Varchar(10)
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
  Declare @p_unidade                Int
  Declare @Result                   Int

  Set @Result = 0

  declare c_unidade cursor for
     select a.sq_unidade, a.sq_unidade_pai, IsNull(c.nome, -1) sq_pessoa_titular,
            IsNull(e.nome, -1) sq_pessoa_substituto
      from eo_unidade a
           left outer join eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and b.tipo_respons = 'T' and b.fim is null)
           left outer join co_pessoa       c on (b.sq_pessoa  = c.sq_pessoa)
           left outer join eo_unidade_resp d on (a.sq_unidade = d.sq_unidade and d.tipo_respons = 'S' and d.fim is null)
           left outer join co_pessoa       e on (d.sq_pessoa  = e.sq_pessoa)
     where a.sq_unidade  = @p_unidade

 -- Recupera as informações da opção à qual a solicitação pertence
 select @w_acesso_geral         = a.acesso_geral,         @w_sq_servico           = a.sq_menu, 
        @w_modulo               = a.sq_modulo,            @w_sigla                = a.sigla,
        @w_username             = b.sq_pessoa,            @w_sq_unidade_lotacao   = b.sq_unidade, 
        @w_gestor_seguranca     = b.gestor_seguranca,     @w_gestor_sistema       = b.gestor_sistema, 
        @w_usuario_ativo        = b.ativo,                @w_sq_unidade_executora = a.sq_unid_executora,
        @w_consulta_opiniao     = a.consulta_opiniao,     
        @w_envia_email          = a.envia_email,          @w_exibe_relatorio      = a.exibe_relatorio, 
        @w_vinculacao           = a.vinculacao,           @w_sq_siw_tramite       = d.sq_siw_tramite, 
        @w_solicitante          = d.solicitante,          @w_cadastrador          = d.cadastrador, 
        @w_unidade_solicitante  = d.sq_unidade,           @w_sq_pessoa_executor   = d.executor, 
        @w_opiniao_solicitante  = d.opiniao,              @w_ordem                = e.ordem, 
        @w_sq_cc                = d.sq_cc, 
        @w_sigla_situacao       = e.sigla,                @w_ativo                = e.ativo, 
        @w_chefia_imediata      = e.chefia_imediata,      @w_sq_pessoa_titular    = IsNull(f.sq_pessoa,-1), 
        @w_sq_pessoa_substituto = IsNull(g.sq_pessoa,-1), @w_sq_endereco_unidade  = h.sq_pessoa_endereco,
        @w_executor             = d.executor
   from sg_autenticacao        b,
        siw_solicitacao        d
           inner      join siw_menu               a on (a.sq_menu                = d.sq_menu)
           inner      join siw_tramite            e on (d.sq_siw_tramite         = e.sq_siw_tramite)
           inner      join eo_unidade             h on (d.sq_unidade             = h.sq_unidade)
           left outer join eo_unidade_resp        f on (d.sq_unidade             = f.sq_unidade and
                                                        f.tipo_respons           = 'T'          and
                                                        f.fim                    is not null
                                                       )
           left outer join eo_unidade_resp        g on (d.sq_unidade             = g.sq_unidade and
                                                        g.tipo_respons           = 'T'          and
                                                        g.fim                    is not null
                                                       )
  where d.sq_siw_solicitacao     = @p_solicitacao
    and b.sq_pessoa              = @p_usuario
  
Set @Result = 0
 
 -- Verifica se o usuário está ativo
 If @w_usuario_ativo = 'N' Begin
   -- Se não estiver, retorna 0
   Return(@Result)
 End
 -- Verifica se o usuário é o cadastrador
 If @p_usuario = @w_cadastrador Begin Set @Result = 1 End
 
 -- Verifica se o usuário é o executor
 If @p_usuario = @w_executor Begin Set @Result = 1 End
 
 -- Verifica se o usuário é o solicitante
 If @w_solicitante = @p_usuario Begin 
    Set @Result                 = @Result + 2 
    Set @w_unidade_beneficiario = @w_sq_unidade_lotacao
 End Else Begin 
    -- Verifica se o usuário é interessado na demanda ou se já participou em algum momento
    select @w_existe = count(*)
      from gd_demanda_interes a
     where a.sq_siw_solicitacao = @p_solicitacao
       and a.sq_pessoa          = @p_usuario
    If @w_existe > 0 Set @Result = @Result + 2 
    Else Begin
      select @w_existe = count(*)
        from gd_demanda_log a
       where a.sq_siw_solicitacao = @p_solicitacao
         and a.destinatario      = @p_usuario
       If @w_existe > 0 Set @Result = @Result + 2 
       Else Begin
          -- Verifica se o usuário é interessado no projeto ou se já participou em algum momento
          select @w_existe = count(*)
            from pj_projeto_interes a
           where a.sq_siw_solicitacao = @p_solicitacao
             and a.sq_pessoa          = @p_usuario
          If @w_existe > 0 Set @Result = @Result + 2 
          Else Begin
            select @w_existe = count(*)
              from pj_projeto_log a
             where a.sq_siw_solicitacao = @p_solicitacao
               and a.destinatario      = @p_usuario
             If @w_existe > 0 Set @Result = @Result + 2  
          End
       End
    End
    
    -- recupera o código e a lotação do solicitante, para verificar, mais abaixo,
    -- se o usuário é chefe dele
    select @w_solicitante = a.solicitante, @w_unidade_beneficiario = b.sq_unidade
      from siw_solicitacao a, sg_autenticacao b
     where a.solicitante        = b.sq_pessoa
       and a.sq_siw_solicitacao = @p_solicitacao
 End
 
 -- Se o serviço for vinculado à unidade
 If @w_vinculacao = 'U'
    -- Verifica se o usuário está lotado ou se é titular/substituto 
    -- da unidade de CADASTRAMENTO da solicitação
    If @w_sq_unidade_lotacao   = @w_unidade_solicitante or
       @w_sq_pessoa_titular    = @p_usuario             or
       @w_sq_pessoa_substituto = @p_usuario

       Set @Result = @Result + 4 
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
           where a.sq_siw_solicitacao = @p_solicitacao
             and a.sq_unidade         = @w_sq_unidade_lotacao
          If @w_existe > 0 Set @Result = @Result + 4 
          Else Begin
          
             -- Verifica se a unidade do usuário é uma das envolvidas na execução do projeto
             select @w_existe = count(*) 
               from pj_projeto_envolv a
              where a.sq_siw_solicitacao = @p_solicitacao
                and a.sq_unidade         = @w_sq_unidade_lotacao
             If @w_existe > 0 Set @Result = @Result + 4
          End
       End
    End
 -- Caso contrário, se o serviço for vinculado à pessoa
 Else Begin

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
           where a.sq_siw_solicitacao = @p_solicitacao
             and a.sq_unidade         = @w_unidade_beneficiario
          If @w_existe > 0 Set @Result = @Result + 4 
          Else Begin
              -- Verifica se a unidade do usuário é uma das envolvidas na execução do projeto
              select @w_existe = count(*)
                from pj_projeto_envolv a
               where a.sq_siw_solicitacao = @p_solicitacao
                 and a.sq_unidade         = @w_unidade_beneficiario
              If @w_existe > 0 Set @Result = @Result + 4
          End
       End
    End
 End
 
 -- Verifica se o usuário é gestor do módulo à qual a solicitação pertence
 select @w_existe = count(*)
   from sg_pessoa_modulo a
  where a.sq_pessoa = @p_usuario
    and a.sq_modulo = @w_modulo
 If @w_existe > 0 
    Set @Result = @Result + 8
 Else Begin
    -- Verifica se o usuário tem visão geral no centro de custos ao qual a solicitação está vinculada 
    select @w_existe = count(*) 
      from siw_pessoa_cc a 
     where a.sq_pessoa = @p_usuario 
       and a.sq_menu   = @w_sq_servico 
       and a.sq_cc     = @w_sq_cc
      If @w_existe > 0 Set @Result = @Result + 8 
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
       If @w_existe > 0 Begin Set @Result = @Result + 16 End
    End
 -- Outra possibilidade é a solicitação estar concluída e pendente de opinião pelo
 -- solicitante
End 
Else 
 If @w_sigla_situacao = 'AT' and  @w_solicitante = @p_Usuario and @w_consulta_opiniao = 'S' and @w_opiniao_solicitante is null Begin
    Set @Result = @Result + 16
 End 
 Else Begin
    -- Outra possibilidade é o trâmite ser cumprido por uma pessoa que tenha
    -- permissão para isso
    select @w_existe = count(*)
      from sg_tramite_pessoa a 
     where a.sq_pessoa          = @p_usuario
       and a.sq_pessoa_endereco = @w_sq_endereco_unidade 
       and a.sq_siw_tramite     = @w_sq_siw_tramite
    If @w_existe > 0 Begin 
       Set @Result = @Result + 16 
    End Else Begin
       -- Outra possibilidade é a solicitação ter sido encaminhada de modo pessoal
       If @w_executor = @p_usuario Begin
          Set @Result = @Result + 16 
       End
    End
 End

 return(@Result)
end
