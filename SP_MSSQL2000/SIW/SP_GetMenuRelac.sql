alter procedure Sp_GetMenuRelac
   (@p_menu    int   = null,
    @p_acordo     varchar(1)  = null,
    @p_acao       varchar(1)  = null,
    @p_viagem     varchar(1)  = null,
    @p_restricao  varchar(20) = null
   ) as
    declare @l_modulo varchar(200);
    set  @l_modulo  = '';
begin
   If @p_restricao = 'CLIENTES' begin
      -- Recupera a lista de serviços que podem ser vinculados ao serviço informado
      If @p_acordo = 'N' begin
         set  @l_modulo = @l_modulo+',AC';
      End 
      If @p_acao = 'N' begin
          set @l_modulo = @l_modulo+',IS';
      End 
      If @p_viagem = 'N' begin
         set  @l_modulo = @l_modulo+',PD';
      End 
      set  @l_modulo = substring(@l_modulo,2,200);
      
         select distinct
                a.servico_fornecedor,         b.nome as nm_servico_fornecedor, b.sigla as sg_servico_fornecedor,
                a.servico_cliente,            c.ordem as or_servico_cliente,   c.nome as nm_servico_cliente,    c.sigla as sg_servico_cliente,
                d.ordem as or_modulo_cliente, d.sigla as sg_modulo_cliente,    d.nome as nm_modulo_cliente
           from siw_menu_relac                  a
                inner   join siw_menu           b on (a.servico_fornecedor = b.sq_menu)
                inner   join siw_menu           c on (a.servico_cliente    = c.sq_menu)
                  inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
                  inner join siw_cliente_modulo e on (c.sq_modulo          = e.sq_modulo and
                                                      c.sq_pessoa          = e.sq_pessoa)
          where a.servico_fornecedor = @p_menu
            and d.sigla              not in ('GD')
            and substring(c.sigla,1,3)  <> 'GDT'
            and (@l_modulo is null or (@l_modulo is not null and charindex(d.sigla,@l_modulo) = 0))
          order by b.nome, c.nome;
   end Else if @p_restricao = 'SERVICO' begin
      -- Recupera a lista de serviços aos quais o serviço informado pode ser vinculado
      If @p_acordo = 'N' begin
         set @l_modulo = @l_modulo+',AC';
      End 
      If @p_acao = 'N' begin
         set @l_modulo = @l_modulo+',IS';
      End 
      If @p_viagem = 'N' begin
         set @l_modulo = @l_modulo+',PD';
      End
      set @l_modulo = substring(@l_modulo,2,200);
      
         select distinct(a.servico_cliente), a.servico_fornecedor,
                b.nome as nm_servico_cliente,
                c.nome as nm_servico_fornecedor, d.nome as nm_modulo_fornecedor,
                a.servico_fornecedor as sq_menu, c.nome
           from siw_menu_relac                  a
                inner   join siw_menu           b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu           c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
                  inner join siw_cliente_modulo e on (c.sq_modulo          = e.sq_modulo and
                                                      c.sq_pessoa          = e.sq_pessoa)
          where a.servico_cliente = @p_menu
            and (@l_modulo is null or (@l_modulo is not null and charindex(d.sigla,@l_modulo) = 0))
          order by b.nome, c.nome;
    end Else begin
     
         select a.servico_cliente, a.servico_fornecedor, a.sq_siw_tramite,
                b.nome as nm_servico_cliente,
                c.nome as nm_servico_fornecedor, d.nome as nm_modulo_fornecedor,
                e.nome as nm_tramite,
                a.servico_fornecedor as sq_menu, c.nome
           from siw_menu_relac           a
                inner   join siw_menu    b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu    c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo  d on (c.sq_modulo          = d.sq_modulo)
                inner   join siw_tramite e on (a.sq_siw_tramite     = e.sq_siw_tramite)
          where a.servico_cliente = @p_menu
            and ((@p_restricao is null) or (@p_restricao is not null and a.sq_siw_tramite = @p_restricao))
          order by b.nome, c.nome, e.nome;
   End 
end

