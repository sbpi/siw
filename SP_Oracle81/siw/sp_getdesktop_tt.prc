create or replace procedure SP_GetDeskTop_TT
   (p_usuario   in number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera as ligações do usuário em aberto
   open p_result for
      select (x.existe + y.existe) existe from
      (select /*+ ordered */ count(*) existe
         from tt_usuario            b,
              tt_ligacao a
        where (b.sq_usuario_central = a.sq_usuario_central and
               a.trabalho           is null
              )
          and b.usuario = p_usuario
      ) x,
      (select /*+ ordered */ count(*) existe
         from tt_usuario                       b,
              tt_ramal_usuario c,
              tt_ligacao            a
        where (c.sq_usuario_central = b.sq_usuario_central)
          and (c.sq_ramal           = a.sq_ramal and
               a.trabalho           is null and
               a.sq_usuario_central is null and
               trunc(a.data)        between c.inicio and Nvl(c.fim,sysdate)
              )
          and b.usuario = p_usuario
      ) y;
end SP_GetDeskTop_TT;
/

