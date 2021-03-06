SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_GetTramiteList
   (@p_chave     int
   ) as
begin
   -- Recupera os dados de um trâmite
      select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem, 
             a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
             case a.chefia_imediata
                when 'S' then 'Chefia da unidade solicitante'
                when 'U' then 'Chefia e usuários com  permissão'
                when 'N' then 'Apenas usuários com permissão'
             end nm_chefia
      from siw_tramite a 
      where a.sq_menu = @p_chave
end




GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

