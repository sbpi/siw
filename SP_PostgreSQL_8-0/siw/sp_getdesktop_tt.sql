create or replace FUNCTION SP_GetDeskTop_TT
   (p_usuario   numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as ligações do usuário em aberto
   open p_result for
      select (x.existe + y.existe) as existe 
        from (select /*+ ordered */ count(*) as existe
                from tt_usuario            b
                     inner join tt_ligacao a on (b.sq_usuario_central = a.sq_usuario_central and
                                                 a.trabalho           is null
                                                )
               where b.usuario = p_usuario
             ) x,
      (select /*+ ordered */ count(*) as existe
         from tt_usuario                  b
              inner join tt_ramal_usuario c on (c.sq_usuario_central = b.sq_usuario_central)
              inner join tt_ligacao       a on (c.sq_ramal           = a.sq_ramal and
                                                a.trabalho           is null and
                                                a.sq_usuario_central is null and
                                                trunc(a.data)        between c.inicio and Nvl(c.fim,now())
                                               )
        where b.usuario = p_usuario
      ) y;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;