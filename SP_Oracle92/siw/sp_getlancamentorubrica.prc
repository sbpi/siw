create or replace procedure SP_GetLancamentoRubrica
   (p_chave                in number    default null,
    p_lancamento_doc       in number,
    p_sq_rubrica_origem    in number    default null,
    p_sq_rubrica_destino   in number    default null,
    p_result    out sys_refcursor
   ) is
begin
   open p_result for
      select a.sq_lancamento_rubrica, a.sq_rubrica_origem, a.sq_rubrica_destino,
             a.sq_lancamento_doc, a.valor,
             b.nome nm_rubrica_origem, b.codigo cd_rubrica_origem, b.sq_projeto_rubrica,
             c.nome nm_rubrica_destino, c.codigo cd_rubrica_destino
        from fn_lancamento_rubrica a
             inner join pj_rubrica b on (a.sq_rubrica_origem  = b.sq_projeto_rubrica)
             left  join pj_rubrica c on (a.sq_rubrica_destino = c.sq_projeto_rubrica)
       where a.sq_lancamento_doc   = p_lancamento_doc
         and (p_chave              is null or (p_chave              is not null and a.sq_lancamento_rubrica = p_chave))
         and (p_sq_rubrica_origem  is null or (p_sq_rubrica_origem  is not null and a.sq_rubrica_origem     = p_sq_rubrica_origem))
         and (p_sq_rubrica_destino is null or (p_sq_rubrica_destino is not null and a.sq_rubrica_destino    = p_sq_rubrica_destino));
End SP_GetLancamentoRubrica;
/
