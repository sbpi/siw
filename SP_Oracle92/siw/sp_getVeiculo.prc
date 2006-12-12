create or replace procedure SP_GetVeiculo
   (p_chave             in number default null,
    p_chave_aux         in number default null,
    p_cliente           in varchar2, 
    p_placa             in varchar2 default null, 
    p_alugado           in varchar2 default null,     
    p_ativo             in varchar2 default null, 
    p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_veiculo chave, a.sq_tipo_veiculo, a.cliente, a.placa, a.marca, a.modelo, a.combustivel, 
                a.tipo, a.potencia, a.cilindrada, a.ano_modelo, a.ano_fabricacao, a.renavam, a.chassi, a.alugado, a.ativo,
                case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo,
                case a.alugado when 'S' Then 'Sim' Else 'Não' end  nm_alugado
           from sr_veiculo   a
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_veiculo       = p_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_tipo_veiculo  = p_chave_aux))
        and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))  
        and ((p_alugado   is null) or (p_alugado   is not null and a.alugado          = p_alugado))                    
        and ((p_placa     is null) or (p_placa     is not null and a.placa            = p_placa));
end SP_GetVeiculo;
/
