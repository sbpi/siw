create or replace procedure SP_GetSolicCotacao
   (p_cliente   in  number   default null,
    p_chave     in  number   default null,
    p_moeda     in  number   default null,
    p_inicio    in  date     default null,
    p_fim       in  date     default null,
    p_restricao in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
  If p_restricao is null Then
    open p_result for 
      select a.sq_siw_solicitacao,     a.sq_moeda sq_moeda_solic,
             b.codigo cd_moeda_solic,  b.nome nm_moeda_solic,     b.sigla sg_moeda_solic, b.simbolo sb_moeda_solic,
             c.sq_moeda sq_moeda_cot,  c.valor vl_cotacao,
             d.codigo cd_moeda_cot,    d.nome nm_moeda_cot,       d.sigla sg_moeda_cot,   d.simbolo sb_moeda_cot
        from siw_solicitacao                a
             inner   join siw_menu         a1 on (a.sq_menu            = a1.sq_menu)
             inner   join co_moeda          b on (a.sq_moeda           = b.sq_moeda)
             inner   join siw_solic_cotacao c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
               inner join co_moeda          d on (c.sq_moeda           = d.sq_moeda)
       where a1.sq_pessoa = p_cliente
         and (p_chave     is null or (p_chave  is not null and a.sq_siw_solicitacao = p_chave))
         and (p_moeda     is null or (p_moeda  is not null and c.sq_moeda           = p_moeda));
  End If;
end SP_GetSolicCotacao;
/
