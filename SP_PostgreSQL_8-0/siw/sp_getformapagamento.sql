create or replace FUNCTION SP_GetFormaPagamento
   (p_cliente   numeric,
    p_chave     numeric,
    p_chave_aux varchar,
    p_restricao varchar,
    p_ativo     varchar,
    p_sigla     varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_forma_pagamento, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                c.sq_menu, c.nome as nm_menu, c.sigla as sg_menu
           from co_forma_pagamento              a
                inner   join siw_menu_forma_pag b on (a.sq_forma_pagamento = b.sq_forma_pagamento)
                  inner join siw_menu           c on (a.cliente            = c.sq_pessoa and
                                                      b.sq_menu            = c.sq_menu and
                                                      c.sigla              = p_chave_aux
                                                     )
          where a.cliente  = p_cliente
            and (p_chave   is null or (p_chave   is not null and b.sq_menu = p_chave_aux and a.sq_forma_pagamento = p_chave))
            and (p_ativo   is null or (p_ativo   is not null and a.ativo   = p_ativo))
            and (p_sigla   is null or (p_sigla   is not null and a.sigla   = p_sigla))
         order by 2;
   elsif p_restricao = 'REGISTRO' then
      open p_result for 
         select a.sq_forma_pagamento as chave, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' Then 'Sim' Else 'Não' end  as nm_ativo
           from co_forma_pagamento   a
          where a.cliente        = p_cliente
            and (p_sigla is null or (p_sigla   is not null and a.sigla   = p_sigla))
            and (p_chave is null or (p_chave is not null and a.sq_forma_pagamento = p_chave));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;