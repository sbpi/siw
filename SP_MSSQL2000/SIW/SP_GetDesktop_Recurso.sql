alter procedure sp_getDesktop_Recurso(@p_cliente int, @p_usuario int) as
begin
   -- Recupera os itens do pool de recursos que o usuário pode manipular
     select a.qtd as qt_visao, b.qtd as qt_gestao,
            c.nm_modulo, c.nm_opcao, c.link, c.p1, c.p2, c.p3, c.p4, c.sigla
       from (select count(x.sq_recurso) as qtd from eo_recurso x where x.cliente = @p_cliente and x.ativo = 'S' and x.exibe_mesa = 'S' and 0 < dbo.acesso_recurso(sq_recurso, @p_usuario)) a,
            (select count(x.sq_recurso) as qtd from eo_recurso x where x.cliente = @p_cliente and x.ativo = 'S' and x.exibe_mesa = 'S' and 4 = dbo.acesso_recurso(sq_recurso, @p_usuario)) b,
            (select y.nome as nm_modulo, x.nome as nm_opcao, x.link, x.p1, x.p2, x.p3, x.p4, x.sigla
               from co_pessoa               w
                    inner   join siw_menu   x on (w.sq_pessoa_pai = x.sq_pessoa and lower(link) like '%recurso.php?par=inicial')
                      inner join siw_modulo y on (x.sq_modulo     = y.sq_modulo)
              where w.sq_pessoa = @p_usuario
            )  c
      where a.qtd > 0 or b.qtd > 0;
end;
