create or replace procedure SP_GetEsquemaTabela
   (p_restricao         in varchar2  default null,
    p_sq_esquema        in number,
    p_sq_esquema_tabela in number    default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os tipos de apoio do status de um projeto
   open p_result for
      select a.sq_esquema_tabela, a.sq_esquema, a.sq_tabela, a.ordem, a.elemento,
             b.nome nm_tabela, c.qtd_coluna, d.campo_externo, d.ordem or_coluna,
             d.mascara_data, d.valor_default,
             e.nome cl_nome, e.obrigatorio cl_obrigatorio, e.tamanho cl_tamanho,
             decode(e.sq_dado_tipo,1,'B_VARCHAR',2,'B_INTEGER',3,'B_DATE',4,'B_VARCHAR',6,'B_VARCHAR') nm_tipo
        from dc_esquema_tabela                     a,
             dc_tabela           b,
             (select x.sq_esquema_tabela, count(*) qtd_coluna
                                  from dc_esquema_atributo x
                              group by sq_esquema_tabela
                                )                  c,
             dc_esquema_atributo d,
               dc_coluna           e
       where (a.sq_tabela = b.sq_tabela)
         and (a.sq_esquema_tabela = c.sq_esquema_tabela (+))
         and (a.sq_esquema_tabela = d.sq_esquema_tabela (+))
         and (d.sq_coluna           = e.sq_coluna(+))
         and a.sq_esquema = p_sq_esquema
         and ((p_sq_esquema_tabela is null) or (p_sq_esquema_tabela is not null and a.sq_esquema_tabela = p_sq_esquema_tabela));
end SP_GetEsquemaTabela;
/
