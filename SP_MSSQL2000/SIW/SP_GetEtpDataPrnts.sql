alter procedure dbo.SP_GetEtpDataPrnts
   (@p_chave   int
   ) as
Begin
   -- Recupera as etapas acima da informada
      select dbo.montaOrdem(@p_chave, null) ordem;
End
