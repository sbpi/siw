create or replace procedure SP_GetFormaPagamento
   (p_cliente   in number,
    p_chave     in number   default null,
    p_chave_aux in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os tipos de contrato do cliente
      open p_result for
         select a.sq_forma_pagamento, a.cliente, a.nome, a.sigla, a.ativo,
                decode(a.ativo,'S','Sim','Não') nm_ativo,
                c.sq_menu, c.nome nm_menu, c.sigla sg_menu
           from co_forma_pagamento              a,
                siw_menu_forma_pag b,
                  siw_menu           c
          where (a.sq_forma_pagamento = b.sq_forma_pagamento)
            and (a.cliente            = c.sq_pessoa and
                 b.sq_menu            = c.sq_menu and
                 c.sigla              = p_chave_aux
                )
            and a.cliente  = p_cliente
            and (p_chave   is null or (p_chave   is not null and b.sq_menu = p_chave_aux and a.sq_forma_pagamento = p_chave))
         order by 2;
   End If;
end SP_GetFormaPagamento;
/

