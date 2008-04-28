CREATE OR REPLACE FUNCTION siw.SP_GetDeskTop_TT
   (p_usuario   numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as ligações do usuário em aberto
   open p_result for
      select (x.existe + y.existe) as existe 
        from (select /*+ ordered */ count(*) as existe
                from siw.tt_usuario            b
                     inner join siw.tt_ligacao a on (b.sq_usuario_central = a.sq_usuario_central and
                                                 a.trabalho           is null
                                                )
               where b.usuario = p_usuario
             ) x,
      (select /*+ ordered */ count(*) as existe
         from siw.tt_usuario                  b
              inner join siw.tt_ramal_usuario c on (c.sq_usuario_central = b.sq_usuario_central)
              inner join siw.tt_ligacao       a on (c.sq_ramal           = a.sq_ramal and
                                                a.trabalho           is null and
                                                a.sq_usuario_central is null and
                                                trunc(a.data)        between c.inicio and Nvl(c.fim,sysdate)
                                               )
        where b.usuario = p_usuario
      ) y;
      return p_result;
end $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetDeskTop_TT
   (p_usuario   numeric) OWNER TO siw;
