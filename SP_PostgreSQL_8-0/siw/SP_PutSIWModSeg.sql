create or replace function SP_PutSIWModSeg
   (p_operacao                 varchar,
    p_objetivo_especifico      varchar,
    p_sq_modulo                numeric,
    p_sq_segmento              numeric,
    p_comercializar            varchar,
    p_ativo                    varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_mod_seg (objetivo_especif, sq_modulo, sq_segmento, comercializar, ativo)
      values ( trim(p_objetivo_especifico),
               p_sq_modulo,
               p_sq_segmento,
               p_comercializar,
               p_ativo
              );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_mod_seg set
         objetivo_especif    = trim(p_objetivo_especifico),
         comercializar       = p_comercializar,
         ativo               = p_ativo
      where sq_modulo   = p_sq_modulo
        and sq_segmento = p_sq_segmento;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete from siw_mod_seg 
        where sq_modulo   = p_sq_modulo
          and sq_segmento = p_sq_segmento;
   End If;
end; $$ language 'plpgsql' volatile;