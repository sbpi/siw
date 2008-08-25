create or replace procedure SP_GetTipoLancamento
   (p_chave     in number default null,
    p_cliente   in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor
   ) is
    
   w_projeto number(18);
   w_rubrica number(18);
   w_tipo    varchar2(1);
begin
   If length(p_restricao) = 21 Then
      w_projeto := substr(p_restricao,1,10);
      w_rubrica := substr(p_restricao,11,10);
      w_tipo    := coalesce(substr(p_restricao,21,1),'T');
   End If;
   -- Recupera os tipos de contrato do cliente
   open p_result for 
      select a.sq_tipo_lancamento as chave, a.nome, a.descricao, a.receita, a.despesa, a.ativo,
             case a.receita when 'S' Then 'Sim' Else 'Não' end as nm_receita,
             case a.despesa when 'S' Then 'Sim' Else 'Não' end as nm_despesa,
             case a.ativo   when 'S' Then 'Sim' Else 'Não' end as nm_ativo,
             acentos(a.nome) as ordena
        from fn_tipo_lancamento   a
       where a.cliente     = p_cliente
         and ((p_chave     is null) or (p_chave     is not null and a.sq_tipo_lancamento = p_chave))
         and ((p_restricao is null) or 
              (p_restricao is not null and 
               ((substr(p_restricao,3,1) = 'R' and a.receita = 'S') or 
                (substr(p_restricao,3,1) = 'D' and a.despesa = 'S') or
                0 < (select count(*) 
                       from pd_vinculo_financeiro x 
                      where x.sq_siw_solicitacao = w_projeto
                        and x.sq_projeto_rubrica = coalesce(w_rubrica,x.sq_projeto_rubrica)
                        and x.sq_tipo_lancamento = a.sq_tipo_lancamento
                        and (w_tipo              = 'T' or
                             (w_tipo             <> 'T' and
                              ((w_tipo           = 'D' and x.diaria     = 'S') or
                               (w_tipo           = 'H' and x.hospedagem = 'S') or
                               (w_tipo           = 'V' and x.veiculo    = 'S') or
                               (w_tipo           = 'S' and x.seguro     = 'S') or
                               (w_tipo           = 'B' and x.bilhete    = 'S')
                              )
                             )
                            )
                    )
               )
              )
             );
end SP_GetTipoLancamento;
/
