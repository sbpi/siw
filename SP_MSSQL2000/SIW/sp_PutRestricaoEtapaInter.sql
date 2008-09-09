alter procedure dbo.sp_PutRestricaoEtapaInter
   (@p_operacao         varchar(1),
    @p_chave            int,
    @p_sq_projeto_etapa int
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_etapa_interessado 
          (sq_unidade, sq_projeto_etapa) 
       values
          (@p_chave,   @p_sq_projeto_etapa);
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete siw_etapa_interessado 
       where (@p_chave              is null or (@p_chave                is not null and sq_unidade  = @p_chave))
         and (@p_sq_projeto_etapa   is null or (@p_sq_projeto_etapa     is not null and sq_projeto_etapa  = @p_sq_projeto_etapa));
   End
end
