alter procedure dbo.sp_GetMenuList
   (@p_cliente   int,
    @p_operacao  varchar(40),
    @p_chave     int  = null,
    @p_modulo    int  = null
   ) as
begin
   If upper(@p_operacao) = 'L' begin
      -- Recupera os links que referenciam rotinas do sistema
        select a.sq_menu, a.nome, a.link, a.ativo,
               b.nome, 
               dbo.MontaOrdemMenu(a.sq_menu) or_menu,
               dbo.MontaNomeMenu(a.sq_menu)  nm_menu
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
           and a.externo   = 'N'
           and a.link      is not null;
   end else if upper(@p_operacao) = 'NUMERADOR' begin
      -- Recupera os serviços que têm numeração própria
        select a.sq_menu,
               case when a.sq_modulo is null or @p_modulo is not null then a.nome else a.nome + ' (' + b.nome + ')' end nome,
               a.nome as nm_servico,
               a.acesso_geral, a.ultimo_nivel, a.tramite, 
               b.sigla sg_modulo, b.nome nm_modulo
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
           and 'S'         = a.tramite
           and 1           = coalesce(a.numeracao_automatica,0)
           and a.sq_menu   <> coalesce(@p_chave,0)
           and b.sigla     = case when @p_modulo is null then b.sigla else @p_modulo end
        order by dbo.acentos(a.nome);
   end else if upper(@p_operacao) = 'X' begin
      -- Recupera os links vinculados a serviços
        select a.sq_menu,
               case when a.sq_modulo is null or @p_modulo is not null then a.nome else a.nome + ' (' + b.nome + ')' end nome,
               a.nome as nm_servico, a.sigla sg_servico,
               a.acesso_geral, a.ultimo_nivel, a.tramite, 
               b.sigla sg_modulo, b.nome nm_modulo, a.sq_modulo
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
           and a.tramite   = 'S'
           and b.sigla     = coalesce(cast(@p_modulo as varchar),b.sigla)
        order by dbo.acentos(a.nome);
   end else if upper(@p_operacao) = 'XVINC' begin
      -- Recupera os links vinculados a serviços
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome + ' (' + b.nome + ')' end nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
           and a.tramite   = 'S'
           --and a.sq_menu   <> @p_chave
        order by dbo.acentos(a.nome);        
   end else if upper(@p_operacao) <> 'I' and upper(@p_operacao) <> 'H' begin
      -- Se for alteração, evita a exibição do próprio registro e dos seus subordinados
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome + ' (' + b.nome + ')' end nome 
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
           and a.sq_menu not in (dbo.SP_fGetPlano(@p_chave,'DOWN'))
        order by dbo.acentos(a.nome);
   end else begin
      -- Recupera os links existentes para o cliente informado
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome + ' (' + b.nome + ')' end nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = @p_cliente
        order by dbo.acentos(a.nome);
    End
end