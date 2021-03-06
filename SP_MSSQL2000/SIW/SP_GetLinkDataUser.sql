alter procedure dbo.SP_GetLinkDataUser(
    @p_cliente   int,
    @p_sq_pessoa int         =null,
    @p_restricao varchar(100)=null
   ) as
begin
  -- Recupera os links permitidos ao usuário informado (pessoa > 0)  ou ao cliente informado (pessoa = 0)
  If @p_restricao is not null
     If upper(@p_restricao) = 'IS NULL'
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, IsNull(a.target,'content') target, a.ultimo_nivel, a.ativo, IsNull(b.filho,0) Filho
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) Filho from siw_menu x where ativo = 'S' and ultimo_nivel = 'N' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      is null
              and a.sq_pessoa        = @p_cliente
              and (@p_sq_pessoa       <= 0 or (@p_sq_pessoa > 0 and dbo.marcado(a.sq_menu, @p_sq_pessoa, null, null, null) > 0))
           order by 4,2;
     Else
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, IsNull(a.target,'content') target, a.ultimo_nivel, a.ativo, IsNull(b.filho,0) Filho
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) Filho from siw_menu x where ativo = 'S' and ultimo_nivel = 'N' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      = cast(@p_restricao as int)
              and a.sq_pessoa        = @p_cliente
              and (@p_sq_pessoa       <= 0 or (@p_sq_pessoa > 0 and dbo.marcado(a.sq_menu, @p_sq_pessoa, null, null, null) > 0))
           order by 4,2;
End

