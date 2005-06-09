create or replace procedure SP_GetTTRamalUsuarioAtivo
   (p_cliente         in  number,
    p_result          out sys_refcursor) is
begin
   -- Recupera os tipos de Tabela
     open p_result for
   select a.sq_pessoa, a.nome_resumido nm_usuario_resumido, a.nome nm_usuario_completo,
          c.ativo,
          d.sigla sg_unidade, d.nome nm_unidade, 
          e.nome nm_local,
          g.codigo
     from co_pessoa                             a
       left outer   join sg_autenticacao   c on (a.sq_pessoa          = c.sq_pessoa) 
         left outer join eo_unidade        d on (c.sq_unidade         = d.sq_unidade)
         left outer join eo_localizacao    e on (c.sq_localizacao     = e.sq_localizacao)
       inner        join tt_usuario        b on (a.sq_pessoa          = b.usuario)
         inner      join tt_ramal_usuario  f on (b.sq_usuario_central = f.sq_usuario_central and
                                                 f.fim                  is null)
           inner    join tt_ramal          g on (f.sq_ramal           = g.sq_ramal)
    where a.sq_pessoa_pai   = p_cliente;
end SP_GetTTRamalUsuarioAtivo;
/

