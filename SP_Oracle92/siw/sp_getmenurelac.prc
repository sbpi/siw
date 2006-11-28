create or replace procedure SP_GetMenuRelac
   (p_sq_menu    in number   default null,
    p_restricao  in varchar2 default null,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os serviços vinculados do serviço cliente
   If p_restricao = 'SERVICO' Then
      open p_result for 
         select distinct(a.servico_cliente), a.servico_fornecedor,
                b.nome nm_servico_cliente,
                c.nome nm_servico_fornecedor, d.nome nm_modulo_fornecedor,
                a.servico_fornecedor sq_menu, c.nome nome
           from siw_menu_relac           a
                inner   join siw_menu    b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu    c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo  d on (c.sq_modulo          = d.sq_modulo)
          where a.servico_cliente = p_sq_menu
          order by b.nome, c.nome;
   Else
      open p_result for 
         select a.servico_cliente, a.servico_fornecedor, a.sq_siw_tramite,
                b.nome nm_servico_cliente,
                c.nome nm_servico_fornecedor, d.nome nm_modulo_fornecedor,
                e.nome nm_tramite,
                a.servico_fornecedor sq_menu, c.nome nome
           from siw_menu_relac           a
                inner   join siw_menu    b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu    c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo  d on (c.sq_modulo          = d.sq_modulo)
                inner   join siw_tramite e on (a.sq_siw_tramite     = e.sq_siw_tramite)
          where a.servico_cliente = p_sq_menu
            and ((p_restricao is null) or (p_restricao is not null and a.sq_siw_tramite = p_restricao))
          order by b.nome, c.nome, e.nome;
   End If;
end SP_GetMenuRelac;
/
