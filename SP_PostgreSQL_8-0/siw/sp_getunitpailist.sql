﻿create or replace FUNCTION SP_GetUnitPaiList
   (p_operacao     varchar,
    p_sq_pessoa    numeric,
    p_sq_unidade   numeric,
    p_result      REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_operacao = 'A' Then
   --Recupera a lista de unidades quem podem ser pai
   open p_result for
      select a.sq_unidade, a.nome
        from eo_unidade a
       where sq_pessoa = p_sq_pessoa
         and a.sq_unidade not in (select sq_unidade from connectby('eo_unidade','sq_unidade','sq_unidade_pai',to_char(p_sq_unidade),0) as (sq_unidade numeric, sq_unidade_pai numeric, int level));
   Else
      open p_result for
         select a.sq_unidade, a.nome
           from eo_unidade a, co_pessoa_endereco b 
          where a.sq_pessoa_endereco = b.sq_pessoa_endereco
            and b.sq_pessoa          = p_sq_pessoa;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;