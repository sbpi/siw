create or replace procedure SP_GetTipoLancamento
   (p_chave     in number default null,
    p_cliente   in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de contrato do cliente
   open p_result for 
      select a.sq_tipo_lancamento chave, a.nome, a.descricao, a.receita, a.despesa, a.ativo,
             case a.receita when 'S' Then 'Sim'
                            Else 'Não'
                            end  nm_receita,
             case a.despesa when 'S' Then 'Sim'
                            Else 'Não'
                            end  nm_despesa,
             case a.ativo   when 'S' Then 'Sim'
                            Else 'Não'
                            end  nm_ativo                            
        from fn_tipo_lancamento   a
   where a.cliente     = p_cliente
     and ((p_chave     is null) or (p_chave     is not null and a.sq_tipo_lancamento = p_chave))
     and ((p_restricao is null) or (p_restricao is not null and 
                                    ((substr(p_restricao,3,1) = 'R' and a.receita = 'S') or 
                                     (substr(p_restricao,3,1) = 'D' and a.despesa = 'S')
                                    )
                                   )
         );
end SP_GetTipoLancamento;
/

