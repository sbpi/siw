create or replace procedure SP_GetMenuRelac
   (p_sq_menu    in number   default null,
    p_acordo     in varchar2 default null,
    p_acao       in varchar2 default null,
    p_viagem     in varchar2 default null,
    p_restricao  in varchar2 default null,
    p_result     out sys_refcursor
   ) is
    l_modulo     varchar2(200) := '';
begin
   -- Recupera os serviços vinculados do serviço cliente
   If p_restricao = 'SERVICO' Then
      If p_acordo = 'N' Then
         l_modulo := l_modulo||',AC';
      End If;
      If p_acao = 'N' Then
         l_modulo := l_modulo||',IS';
      End If;
      If p_viagem = 'N' Then
         l_modulo := l_modulo||',PD';
      End If;   
      l_modulo := substr(l_modulo,2,200);
      open p_result for 
         select distinct(a.servico_cliente), a.servico_fornecedor,
                b.nome nm_servico_cliente,
                c.nome nm_servico_fornecedor, d.nome nm_modulo_fornecedor,
                a.servico_fornecedor sq_menu, c.nome nome
           from siw_menu_relac                  a
                inner   join siw_menu           b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu           c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
                  inner join siw_cliente_modulo e on (c.sq_modulo          = e.sq_modulo and
                                                      c.sq_pessoa          = e.sq_pessoa)
          where a.servico_cliente = p_sq_menu
            and (l_modulo is null or (l_modulo is not null and InStr(l_modulo,d.sigla) = 0))
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
