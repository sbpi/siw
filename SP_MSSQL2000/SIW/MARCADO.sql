ALTER  function dbo.MARCADO
  (@p_menu     int,
   @p_pessoa   int,
   @p_endereco int = null,
   @p_tramite  int = null,
   @p_fase     int = null
  ) returns int as
/**********************************************************************************
* Nome      : Marcado
* Finalidade: Verificar se o usuário têm acesso a uma opção, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  28/03/2003, 21:24
* Parâmetros:
*    @p_menu   : chave primária de siw_menu
*    @p_pessoa   : chave de acesso do usuário
*    @p_endereço : opcional. Se informado, restringe a busca a este endereço
*    @p_tramite  : opcional. Se informado, restringe a busca a este trâmite
*    @p_fase     : opcional. Se informado, restringe a busca a esta fase
* Retorno: 
*    3: Se a opção for de acesso geral
*    2: Se o usuário for um gestor de módulo, ou do sistema, ou ainda de segurança
*    1: Se o usuário já tiver esta permissão concedida para o endereço informado
*    0: Se o usuário não tem permissão para a opção informada
***********************************************************************************/
begin
  Declare @w_sq_servico          VarChar(1)
  Declare @w_sq_situacao_servico Int
  Declare @w_sg_modulo           varchar(10)
  Declare @w_sq_modulo           Int
  Declare @w_gestor_seguranca    VarChar(10)
  Declare @w_gestor_sistema      VarChar(10)
  Declare @w_acesso_geral        Int
  Declare @w_vinculo             Int
  Declare @w_existe              Int
  Declare @Result                Int

  Set @Result = 0
 -- Recupera as informações da opção
 select @w_sq_servico       = a.tramite,          @w_sq_modulo      = c.sq_modulo,
        @w_sg_modulo        = c.sigla, 
        @w_gestor_seguranca = gestor_seguranca,   @w_gestor_sistema = gestor_sistema,   
        @w_acesso_geral     = (select count(*) from dbo.SP_fGetMenuList(@p_menu,'UP')),
        @w_vinculo        = d.sq_tipo_vinculo
   from siw_menu        a,
        sg_autenticacao b,
        siw_modulo      c,
        co_pessoa       d
  where a.sq_modulo = c.sq_modulo
    and b.sq_pessoa = d.sq_pessoa
    and a.sq_menu   = @p_menu
    and b.sq_pessoa = @p_pessoa
  
 If @w_acesso_geral > 0 -- Se a opção, ou alguma opção a ela subordinada, é de acesso geral
    Set @Result = 3
 Else If @w_sq_servico = 'N' Begin -- Se a opção não for vinculada a serviço
    -- Verifica se o usuário é gestor do módulo
    If (@w_gestor_sistema = 'S'   and @w_sg_modulo <> 'SG') or
       (@w_gestor_seguranca = 'S' and @w_sg_modulo = 'SG') 
    Begin
       Set @Result = 2
    End 
    Else Begin
      -- Verifica se o usuário é gestor do módulo da opção
      select @w_existe = count(*)
        from sg_pessoa_modulo a
       where a.sq_pessoa = @p_pessoa
         and a.sq_modulo = @w_sq_modulo
         and (@p_endereco is null or (@p_endereco is not null and a.sq_pessoa_endereco = @p_endereco))
      If @w_existe > 0 Begin
         Set @Result = 2
      End Else Begin
         -- Verifica se o USUÁRIO tem permissão concedida para a opção
          select @w_existe = count(*) from sg_pessoa_menu a
          where a.sq_pessoa = @p_pessoa
            and a.sq_menu   = @p_menu
          If @w_existe > 0 Begin Set @Result = 1 End Else Begin Set @Result = 0 End
         
         -- Verifica se o PERFIL do usuário tem permissão concedida para a opção
         If @Result = 0 Begin
             select @w_existe = count(*) from sg_perfil_menu a, co_tipo_vinculo b, co_pessoa c
              where a.sq_tipo_vinculo = b.sq_tipo_vinculo
                and b.sq_tipo_vinculo = c.sq_tipo_vinculo
                and c.sq_pessoa       = @p_pessoa
                and a.sq_menu         = @p_menu
             If @w_existe > 0 Begin Set @Result = 1 End Else Begin Set @Result = 0 End
         End
      End
    End
 End
 Else Begin -- Se a opção for vinculada a serviço
    -- Recupera o código da situação de cadastramento
    select @w_sq_situacao_servico = sq_siw_tramite
      from siw_tramite
     where sq_menu = @p_menu
       and sigla   = 'CI'

    -- Se o trâmite não foi informado, verifica se o usuário tem alguma permissão ao módulo
    If @p_tramite is null Begin
       If (@w_gestor_sistema   = 'S' and @w_sg_modulo <> 'SG') or
          (@w_gestor_seguranca = 'S' and @w_sg_modulo = 'SG')
       Begin
          Set @Result = 2
       End Else Begin
          -- Verifica se o usuário é gestor do módulo da opção
          select @w_existe = count(*)
            from sg_pessoa_modulo a
           where a.sq_pessoa = @p_pessoa
             and a.sq_modulo = @w_sq_modulo
             and (@p_endereco is null or (@p_endereco is not null and a.sq_pessoa_endereco = @p_endereco))
          If @w_existe > 0 Begin
             Set @Result = 2
          End Else Begin
             -- Verifica se o usuário tem alguma permissão concedida para a opção 
             -- ou se a opção é de acesso geral
             select @w_existe = count(*)
             from sg_tramite_pessoa a,
                  sg_autenticacao   b,
                  siw_menu          c,
                  siw_tramite       d
             where a.sq_pessoa      = b.sq_pessoa
               and a.sq_siw_tramite = d.sq_siw_tramite
               and d.sq_menu        = c.sq_menu
               and c.sq_menu        = @p_menu
               and b.sq_pessoa      = @p_pessoa
             If @w_existe > 0 Begin
                Set @Result = 1
             End Else Begin
                Set @Result = 0
             End
          End
       End
    End Else Begin -- Se o trâmite foi informado
       If @w_sq_situacao_servico = @p_tramite and
          ((@w_gestor_sistema = 'S'   and @w_sg_modulo <> 'SG') or
           (@w_gestor_seguranca = 'S' and @w_sg_modulo = 'SG')
          )
       Begin
          Set @Result = 2
       End Else Begin
          -- Verifica se o usuário tem alguma permissão concedida para a opção
          select @w_existe = count(*)
          from sg_tramite_pessoa   a,
               sg_autenticacao     b,
               siw_menu            c,
               siw_tramite         d
          where a.sq_pessoa      = b.sq_pessoa
            and a.sq_siw_tramite = d.sq_siw_tramite
            and d.sq_menu        = c.sq_menu   
            and c.sq_menu        = @p_menu
            and b.sq_pessoa      = @p_pessoa
            and d.sq_siw_tramite = @p_tramite
          If @w_existe > 0 Begin
             Set @Result = 1
          End Else Begin
             Set @Result = 0
          End
       End
    End
 End
 return(@Result)
end
