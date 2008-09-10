alter procedure dbo.sp_GetMarcado(
    @p_menu     int,
    @p_pessoa   int,
    @p_endereco int = null,
    @p_tramite  int = null,
    @p_fase     int = null
    ) as
begin
  Declare @w_sq_servico          VarChar(1)
  Declare @w_sq_situacao_servico Int
  Declare @w_sg_modulo           varchar(10)
  Declare @w_sq_modulo           Int
  Declare @w_gestor_seguranca    VarChar(10)
  Declare @w_gestor_sistema      VarChar(10)
  Declare @w_acesso_geral        VarChar(10)
  Declare @w_vinculo             Int
  Declare @w_existe              Int
  Declare @Result                Int
  

 select dbo.MARCADO(@p_menu, @p_pessoa, @p_endereco, @p_tramite, @p_fase);
end
