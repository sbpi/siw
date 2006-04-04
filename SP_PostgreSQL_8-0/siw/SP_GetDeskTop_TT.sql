create or replace function SP_GetDeskTop_TT
   (p_usuario   numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera as ligações do usuário em aberto
   open p_result for
      select (x.existe + y.existe) as existe from
      (select count(*) as existe
         from tt_usuario            b
              inner join tt_ligacao a on (b.sq_usuario_central = a.sq_usuario_central and
                                          a.trabalho           is null
                                         )
        where b.usuario = p_usuario
      ) x,
      (select count(*) as existe
         from tt_usuario                       b
              inner      join tt_ramal_usuario c on (c.sq_usuario_central = b.sq_usuario_central)
              inner join tt_ligacao            a on (c.sq_ramal           = a.sq_ramal and
                                                     a.trabalho           is null and
                                                     a.sq_usuario_central is null and
                                                     date_trunc('day',a.data) between c.inicio and coalesce(c.fim,current_timestamp)
                                                    )
        where b.usuario = p_usuario
      ) y;
  return p_result;
end; $$ language 'plpgsql' volatile;
