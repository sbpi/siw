alter procedure dbo.SP_GetVincKindData (@p_sq_tipo_vinculo int) as
begin
   -- Recupera os dados do tipo de vinculo
      select nome, sq_tipo_pessoa, interno, contratado, ativo, padrao, envia_mail_tramite, envia_mail_alerta
      from co_tipo_vinculo 
      where sq_tipo_vinculo = @p_sq_tipo_vinculo;
end
