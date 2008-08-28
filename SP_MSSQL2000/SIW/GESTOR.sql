alter function dbo.Gestor
  (@p_solicitacao int,
   @p_usuario     int
  ) returns varchar as
/**********************************************************************************
* Nome      : Gestor
* Finalidade: Verificar se o usuário é gestor do sistema ou do módulo ao qual a solicitacao pertence
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  09/02/2005, 13:46
* Parâmetros:
*    @p_solicitacao : chave primária de SR_SOLICITACAO
*    @p_usuario   : chave de acesso do usuário
* Retorno: 
*    S: O usuário é gestor do sistema ou do módulo à qual a solicitação pertence
*    N: O usuário não é gestor
***********************************************************************************/
begin
  Declare @w_gestor_sistema         varchar(1);
  Declare @w_usuario_ativo          varchar(1);
  Declare @w_sq_modulo              numeric(18);
  Declare @w_endereco_solic         numeric(18);
  Declare @w_existe                 int;
  Declare @Result                   varchar(1);
  Set @Result = 'N';

 -- Verifica se a solicitação e o usuário informados existem
 select @w_existe = count(*) from siw_solicitacao where sq_siw_solicitacao = @p_solicitacao;
 If @w_existe = 0 Return @Result;
 
 select @w_existe = count(*) from co_pessoa where sq_pessoa = @p_usuario;
 If @w_existe = 0 Return @Result;
 
 -- Recupera as informações da opção à qual a solicitação pertence
 select @w_gestor_sistema = b.gestor_sistema,     @w_usuario_ativo = b.ativo,
        @w_endereco_solic = h.sq_pessoa_endereco, @w_sq_modulo     = i.sq_modulo 
   from sg_autenticacao            b,
        siw_solicitacao            d
           inner   join eo_unidade h on (d.sq_unidade = h.sq_unidade)
           inner   join siw_menu   i on (d.sq_menu    = i.sq_menu)
  where d.sq_siw_solicitacao     = @p_solicitacao
    and b.sq_pessoa              = @p_usuario;
  
 -- Verifica se o usuário está ativo
 If @w_usuario_ativo = 'N' Return(@Result);
 
 -- Verifica se o usuário é gestor do sistema
 If @w_gestor_sistema = 'S' Set @Result = 'S';
 
 -- Verifica se o usuário é gestor do módulo à qual a solicitação pertence
 select @w_existe = count(*)
   from sg_pessoa_modulo a
  where a.sq_pessoa          = @p_usuario
    and a.sq_modulo          = @w_sq_modulo
    and a.sq_pessoa_endereco = @w_endereco_solic;
 If @w_existe > 0 Set @Result = 'S';

 return @Result;
end
