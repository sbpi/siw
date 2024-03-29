alter  procedure Sp_GetVincKindList
   (@p_cliente      int,
    @p_ativo        varchar(1)   = null,
    @p_tipo_pessoa  varchar(60)  = null,
    @p_nome         varchar(20)  = null,
    @p_interno      varchar(1)   = null
    ) as
begin
   -- Recupera os tipos de vinculos existentes
   
      select a.sq_tipo_vinculo, a.nome, a.padrao,
             a.interno, a.contratado, a.envia_mail_tramite, a.envia_mail_alerta,
             a.ativo, b.nome sq_tipo_pessoa
        from co_tipo_vinculo a, 
             co_tipo_pessoa  b
       where a.sq_tipo_pessoa = b.sq_tipo_pessoa
         and a.cliente        = @p_cliente
         and ((@p_ativo       is null) or (@p_ativo       is not null and a.ativo   = @p_ativo))
         and ((@p_tipo_pessoa is null) or (@p_tipo_pessoa is not null and b.nome    = @p_tipo_pessoa))
         and ((@p_nome        is null) or (@p_nome        is not null and upper(a.nome) like '%' + dbo.acentos(@p_nome) + '%'))
         and ((@p_interno     is null) or (@p_interno     is not null and a.interno = @p_interno))
     order by a.interno desc, b.nome, a.ordem;
end 