alter  procedure SP_GetLnkDataPrnts
   (
	@p_chave   int
   ) as
begin
   -- Recupera os links acima do informado
      select sq_menu as sq_pagina, sq_menu_pai as sq_pagina_pai, nome as descricao 
      from siw_menu 
      where sq_menu  in ( select chave from dbo.SP_fGetLnkDataPrnts(@p_chave,'DOWN'))
end