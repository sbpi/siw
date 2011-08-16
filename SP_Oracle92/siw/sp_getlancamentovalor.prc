create or replace procedure SP_GetLancamentoValor
   (p_cliente            in number,
    p_menu               in number   default null,
    p_chave              in number   default null,
    p_sq_lancamento_doc  in number   default null,
    p_sq_valores         in number   default null,
    p_restricao          in varchar2 default null,
    p_result             out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_valores, a.nome as nome, a.tipo as tp_valor, a.ativo, acentos(a.nome) as ordenacao,
                b.sq_lancamento_doc, b.valor,
                c.sq_siw_solicitacao, c.sq_tipo_documento
           from fn_valores                        a
                inner   join fn_documento_valores b on (a.sq_valores         = b.sq_valores)
                  inner join fn_lancamento_doc    c on (b.sq_lancamento_doc  = c.sq_lancamento_doc)
          where a.cliente             = p_cliente
            and (p_chave              is null or (p_chave              is not null and c.sq_siw_solicitacao = p_chave))
            and (p_sq_lancamento_doc  is null or (p_sq_lancamento_doc  is not null and c.sq_lancamento_doc  = p_sq_lancamento_doc))
            and (p_sq_valores         is null or (p_sq_valores         is not null and a.sq_valores         = p_sq_valores));
   Elsif p_restricao = 'EDICAO' Then
      open p_result for 
         select a.sq_valores, a.nome as nome, a.tipo as tp_valor, a.ativo, acentos(a.nome) as ordenacao,
                b.sq_lancamento_doc, b.valor
           from fn_valores                        a
                left    join (select w.sq_valores, w.sq_lancamento_doc, w.valor
                                from fn_documento_valores         w
                                     inner join fn_lancamento_doc x on (w.sq_lancamento_doc = x.sq_lancamento_doc)
                               where x.sq_siw_solicitacao = coalesce(p_chave,0)
                                 and (p_sq_lancamento_doc is null or (p_sq_lancamento_doc is not null and x.sq_lancamento_doc  = p_sq_lancamento_doc))
                             )                    b on (a.sq_valores          = b.sq_valores)
                left    join fn_valores_vinc      d on (a.sq_valores          = d.sq_valores)
          where a.cliente             = p_cliente
            and a.ativo               = 'S'
            and (p_menu              is null or (p_menu               is not null and d.sq_menu     = p_menu))
            and (p_sq_valores        is null or (p_sq_valores         is not null and a.sq_valores  = p_sq_valores));
   End If;
End SP_GetLancamentoValor;
/
