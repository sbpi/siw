create or replace FUNCTION SP_GetMenuRelac
   (p_sq_menu    numeric,
    p_acordo     varchar,
    p_acao       varchar,
    p_viagem     varchar,
    p_restricao  varchar,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
    l_modulo     varchar(200) := '';
    w_tramite    varchar(1);
BEGIN
   -- Verifica se o cliente é um serviço
   select tramite into w_tramite from siw_menu where sq_menu = p_sq_menu;
   
   -- Se não for, recupera os serviços fornecedores a clientes do mesmo módulo   
   If w_tramite = 'N' Then
      open p_result for 
         select distinct a.servico_fornecedor as sq_menu, c.nome
           from siw_menu_relac                  a
                inner   join siw_menu           b on (a.servico_cliente    = b.sq_menu)
                  inner join siw_menu           f on (b.sq_pessoa          = f.sq_pessoa and
                                                      b.sq_modulo          = f.sq_modulo
                                                     )
                inner   join siw_menu           c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
                  inner join siw_cliente_modulo e on (c.sq_modulo          = e.sq_modulo and
                                                      c.sq_pessoa          = e.sq_pessoa)
          where f.sq_menu = p_sq_menu
         order by c.nome;
   ElsIf p_restricao = 'CLIENTES' Then
      -- Recupera a lista de serviços que podem ser vinculados ao serviço informado
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
          where a.servico_fornecedor = p_sq_menu
            and d.sigla              not in ('GD')
            and substr(c.sigla,1,3)  <> 'GDT'
            and (l_modulo is null or (l_modulo is not null and InStr(l_modulo,d.sigla) = 0))
          order by b.nome, c.nome;
   Elsif p_restricao = 'SERVICO' Then
      -- Recupera a lista de serviços aos quais o serviço informado pode ser vinculado
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
                b.nome as nm_servico_cliente,
                c.nome as nm_servico_fornecedor, d.nome as nm_modulo_fornecedor,
                a.servico_fornecedor as sq_menu, c.nome
           from siw_menu_relac                  a
                inner   join siw_menu           b on (a.servico_cliente    = b.sq_menu)
                inner   join siw_menu           c on (a.servico_fornecedor = c.sq_menu)
                  inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
                  inner join siw_cliente_modulo e on (c.sq_modulo          = e.sq_modulo and
                                                      c.sq_pessoa          = e.sq_pessoa)
          where ((b.tramite = 'S' and a.servico_cliente = p_sq_menu) or
                 (b.tramite = 'N' and c.sq_pessoa = (select sq_pessoa from siw_menu where sq_menu = p_sq_menu))
                )
           and (l_modulo is null or (l_modulo is not null and InStr(l_modulo,d.sigla) = 0))
          order by b.nome, c.nome;
   Else
      open p_result for 
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
          where a.servico_cliente = p_sq_menu
            and ((p_restricao is null) or (p_restricao is not null and a.sq_siw_tramite = to_number(p_restricao)))
          order by b.nome, c.nome, e.nome;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;