create or replace procedure SP_GetTTRamalUsuarioAtivo
   (p_cliente         in  number,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera os tipos de Tabela
     open p_result for
   select a.sq_pessoa, a.nome_resumido nm_usuario_resumido, a.nome nm_usuario_completo,
          c.ativo,
          d.sigla sg_unidade, d.nome nm_unidade,
          e.nome nm_local,
          g.codigo
     from co_pessoa                             a,
          sg_autenticacao   c,
          eo_unidade        d,
          eo_localizacao    e,
          tt_usuario        b,
          tt_ramal_usuario  f,
          tt_ramal          g
    where (a.sq_pessoa          = c.sq_pessoa (+))
      and (c.sq_unidade         = d.sq_unidade (+))
      and (c.sq_localizacao     = e.sq_localizacao (+))
      and (a.sq_pessoa          = b.usuario)
      and (b.sq_usuario_central = f.sq_usuario_central and
           f.fim                  is null)
      and (f.sq_ramal           = g.sq_ramal)
      and a.sq_pessoa_pai   = p_cliente;
end SP_GetTTRamalUsuarioAtivo;
/

