create or replace procedure SP_GetTipoDocumento
   (p_chave     in number   default null,
    p_cliente   in number,
    p_menu      in number   default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de contrato do cliente
   open p_result for 
      select a.sq_tipo_documento chave, a.nome, a.sigla, a.detalha_item, a.codigo_externo, a.ativo,
             case a.detalha_item when 'S' Then 'Sim' Else 'Não' end  as nm_detalha_item,
             case a.ativo        when 'S' Then 'Sim' Else 'Não' end  as nm_ativo,
             c.sq_especie_documento, c.nome as nm_especie,   c.sigla as sg_especie,
             c.sq_assunto,           d.codigo as cd_assunto
        from fn_tipo_documento                 a
             left    join (select cliente, count(*) vinculo
                             from fn_tipo_doc_vinc             w
                                  inner join fn_tipo_documento x on (w.sq_tipo_documento = x.sq_tipo_documento)
                            where x.cliente = p_cliente
                           group by cliente
                          )                    b on (a.cliente              = b.cliente)
             left    join pa_especie_documento c on (a.sq_especie_documento = c.sq_especie_documento)
               left  join pa_assunto           d on (c.sq_assunto           = d.sq_assunto)
   where a.cliente = p_cliente 
     and ((p_chave is null) or (p_chave is not null and a.sq_tipo_documento = p_chave))
     and (coalesce(b.vinculo,0) = 0 or p_menu is null or 0 < (select count(*) from fn_tipo_doc_vinc where sq_tipo_documento = a.sq_tipo_documento and sq_menu = coalesce(p_menu,0)));
end SP_GetTipoDocumento;
/
