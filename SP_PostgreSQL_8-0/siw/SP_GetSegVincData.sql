create or replace function SP_GetSegVincData
   (p_sigla          varchar,
    p_sq_segmento    numeric,
    p_nome           varchar,
    p_sq_seg_vinculo numeric,
    p_result         refcursor
   ) returns refcursor as $$
begin
   If p_sigla = 'SEGMENTOVINC' Then
      -- Recupera os dados do vículo do segmento  escolhido
      open p_result for 
         select sq_seg_vinculo, b.sq_tipo_pessoa, b.nome as nm_tipo_pessoa, a.nome as nome_pessoa,
                a.ativo, a.padrao, a.sq_tipo_pessoa, a.interno, a.contratado, a.ordem
           from dm_seg_vinculo a, 
                co_tipo_pessoa      b
          where a.sq_tipo_pessoa = b.sq_tipo_pessoa
            and a.sq_segmento = p_sq_segmento
            and (p_sq_seg_vinculo is null or (p_sq_seg_vinculo is not null and a.sq_seg_vinculo = p_sq_seg_vinculo));
   ElsIf p_sigla = 'SEGMENTOMENU' Then
      -- Recupera os dados do menu do segmento escolhido
      open p_result for 
         select a.nome, b.objetivo_especif as objetivo, c.nome as nm_modulo, a.sq_modulo,
                a.ativo, b.comercializar
           from dm_segmento_menu     a
                  left outer join siw_mod_seg b on (a.sq_segmento = b.sq_segmento and a.sq_modulo = b.sq_modulo) 
                  left outer join siw_modulo  c on (b.sq_modulo = c.sq_modulo)
          where a.sq_segmento = p_sq_segmento; 
   ElsIf p_sigla = 'SEGMENTOMOD' Then
      -- Recupera os dados dos módulos do segmento escolhido
      open p_result for 
         select a.sq_segmento, b.sq_modulo, b.nome as nm_modulo, a.objetivo_especif,
                a.ativo, a.comercializar
           from siw_mod_seg a left outer join siw_modulo b on (a.sq_modulo = b.sq_modulo) 
          where a.sq_segmento = p_sq_segmento;
   End If; 
   return p_result;
end; $$ language 'plpgsql' volatile;