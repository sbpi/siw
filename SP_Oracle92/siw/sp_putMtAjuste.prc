create or replace procedure sp_putMtAjuste
   (p_operacao              in varchar2,
    p_cliente               in number,
    p_usuario               in number,
    p_chave                 in number   default null,
    p_minimo                in number   default null,
    p_consumo               in number   default null,    
    p_ciclo                 in number   default null,
    p_ponto                 in number   default null,
    p_disponivel            in varchar2 default null
   ) is
begin
   If p_operacao = 'A' Then -- Alteração
     update mt_estoque
        set estoque_minimo       = p_minimo,
            consumo_medio_mensal = p_consumo,
            ciclo_compra         = p_ciclo,
            ponto_ressuprimento  = p_ponto,
            disponivel           = p_disponivel
     where sq_estoque = p_chave;
   End If;
end sp_putMtAjuste;
/
