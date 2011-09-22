create or replace function retornaSaldoEstoque(p_material in number, p_data in date default null, p_almox in number default null, p_local in number default null, p_tipo in varchar2 default null) return varchar2 is
  w_qtd  number(18);
  w_val  number(18,2);
  Result varchar2(255);

  cursor c_dados is
    select 'E' as tipo,
           a.sq_almoxarifado,                   b.sq_almoxarifado_local,                         c.sq_entrada_item as item,
           d.quantidade,                        d.valor_total,                                   d.sq_material,
           d2.sq_mtentrada as chave,            d2.armazenamento as ocorrencia
      from mt_almoxarifado                                a
           inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
             inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
               inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item and d.lote_bloqueado = 'N')
                 inner       join mt_entrada             d2 on (d.sq_mtentrada           = d2.sq_mtentrada)
               inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
     where d.sq_material     = p_material
       and d2.armazenamento  < coalesce(p_data,trunc(sysdate))
       and (p_tipo           is null or (p_tipo  is not null and p_tipo                  = 'E'))
       and (p_almox          is null or (p_almox is not null and a.sq_almoxarifado       = p_almox))
       and (p_local          is null or (p_local is not null and b.sq_almoxarifado_local = p_local))
    UNION
    select 'S' as tipo,
           a.sq_almoxarifado,                   b.sq_almoxarifado_local,                         e1.sq_saida_item as item,
           e1.quantidade_entregue,              e1.valor_unitario as valor_total,                d.sq_material,
           e11.sq_mtsaida as chave,             e1.data_efetivacao as ocorrencia
      from mt_almoxarifado                                a
           inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
             inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
               inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item and d.lote_bloqueado = 'N')
               inner         join mt_saida_estoque        e on (c.sq_estoque_item        = e.sq_estoque_item)
                 inner       join mt_saida_item          e1 on (e.sq_saida_item          = e1.sq_saida_item)
                   inner     join mt_saida              e11 on (e1.sq_mtsaida            = e11.sq_mtsaida)
                     inner   join mt_tipo_movimentacao e111 on (e11.sq_tipo_movimentacao = e111.sq_tipo_movimentacao)
                     inner   join siw_solicitacao         f on (e11.sq_siw_solicitacao   = f.sq_siw_solicitacao)
                       inner join siw_tramite            f1 on (f.sq_siw_tramite         = f1.sq_siw_tramite)
               inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
     where d.sq_material      = p_material
       and f1.sigla           = 'AT'
       and e1.data_efetivacao < coalesce(p_data,trunc(sysdate))
       and (p_tipo           is null or (p_tipo  is not null and p_tipo                  = 'S'))
       and (p_almox          is null or (p_almox is not null and a.sq_almoxarifado       = p_almox))
       and (p_local          is null or (p_local is not null and b.sq_almoxarifado_local = p_local))
    order by ocorrencia;
begin
  -- Inicializa as variáveis
  w_val := 0;
  w_qtd := 0;

  -- Verifica o saldo quantitativo e financeiro até a data informada
  for crec in c_dados loop
      If crec.tipo = 'E' Then
         w_val := w_val + crec.valor_total;
         w_qtd := w_qtd + crec.quantidade;
      Else
         w_val := w_val - crec.valor_total;
         w_qtd := w_qtd - crec.quantidade;
      End If;
  end loop;

  Result := to_char(w_val)||'|'||to_char(w_qtd);
  return(Result);
end retornaSaldoEstoque;
/
