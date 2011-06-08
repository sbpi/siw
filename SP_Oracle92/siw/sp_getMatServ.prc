create or replace procedure sp_getMatServ
   (p_cliente       in number,
    p_usuario       in number,
    p_chave         in number    default null,
    p_tipo_material in number    default null,
    p_codigo        in varchar2  default null,
    p_nome          in varchar2  default null,
    p_ativo         in varchar2  default null,
    p_catalogo      in varchar2  default null,
    p_ata_aviso     in varchar2  default null,
    p_ata_invalida  in varchar2  default null,
    p_ata_valida    in varchar2  default null,
    p_aviso         in varchar2  default null,
    p_invalida      in varchar2  default null,
    p_valida        in varchar2  default null,
    p_branco        in varchar2  default null,
    p_arp           in varchar2  default null,
    p_item          in number    default null,
    p_numero_ata    in varchar2  default null,
    p_acrescimo     in varchar2  default null,
    p_restricao     in varchar2  default null,
    p_result        out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'ALOCACAO' or p_restricao = 'VINCULACAO' or p_restricao = 'EDICAOT' or p_restricao = 'EDICAOP' Then
      -- Recupera materiais e serviços
      open p_result for    
         select a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.valor_unit_est as preco_ata, g.codigo_interno as numero_ata, g.fim as validade_ata
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                left      join (select x.sq_siw_solicitacao, x.codigo_interno, x.inicio, x.fim, z.valor_unit_est, z.sq_material
                                  from siw_solicitacao                  x
                                       inner   join siw_menu            y on (x.sq_menu            = y.sq_menu and y.sigla = 'GCZCAD')
                                       inner   join cl_solicitacao_item z on (x.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                 where p_restricao <> 'EDICAOT'
                                   and p_restricao <> 'EDICAOP'
                                   and z.valor_unit_est = (select min(m.valor_unit_est)
                                                             from siw_solicitacao                  k
                                                                  inner   join siw_menu            l on (k.sq_menu            = l.sq_menu and l.sigla = 'GCZCAD')
                                                                  inner   join cl_solicitacao_item m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao)
                                                            where m.sq_material = z.sq_material
                                                          )
                               )                   g  on (a.sq_material         = g.sq_material)
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_catalogo      is null or (p_catalogo      is not null and a.exibe_catalogo   = p_catalogo))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo));
   Elsif p_restricao = 'COMPRA' Then
      -- Recupera materiais e serviços não vinculados a compra
      open p_result for 
         select /*+ ordered */ a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
          where a.cliente         = p_cliente
            and a.sq_material    not in (select x.sq_material from cl_solicitacao_item x where x.sq_siw_solicitacao = p_chave)
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_catalogo      is null or (p_catalogo      is not null and a.exibe_catalogo   = p_catalogo))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo));            
   ElsIf p_restricao = 'EXISTE' or p_restricao = 'EXISTECOD' Then
      -- Verifica se o nome ou a codigo do material ou serviço já foi inserida
      open p_result for 
         select count(a.sq_material) as existe
           from cl_material  a
          where a.cliente        = p_cliente
            and (p_restricao     <> 'EXISTE' or
                 (p_restricao    = 'EXISTE' and sq_material  <> coalesce(p_chave,0))
                )
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno = p_codigo))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)  = acentos(p_nome)));
   ElsIf p_restricao = 'PESQMAT' Then
      -- Verifica se o nome ou a codigo do material ou serviço já foi inserida
      open p_result for 
         select a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                f.ano_corrente, f.dias_validade_pesquisa, f.dias_aviso_pesquisa, f.percentual_acrescimo,
                g.sq_item_fornecedor, g.sq_solicitacao_item, g.fornecedor, g.valor_unidade, g.valor_item, g.ordem,
                g.dias_validade_proposta, g.inicio, g.fim, g.origem,
                to_char(g.inicio,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inicio,
                to_char(g.fim,'dd/mm/yyyy, hh24:mi:ss') as phpdt_fim,
                g.fim-f.dias_aviso_pesquisa as aviso,
                g.fabricante, g.marca_modelo, g.embalagem, g.fator_embalagem,
                case g.origem when 'SA' then 'ARP externa' when 'SG' then 'Governo' when 'SF' then 'Site comercial' else 'Proposta fornecedor' end as nm_origem,
                h.nome as nm_fornecedor, h.nome_resumido as nm_fornecedor_res
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                inner     join cl_item_fornecedor  g  on (a.sq_material         = g.sq_material and
                                                          g.pesquisa            = 'S'
                                                         )
                  inner   join co_pessoa           h  on (g.fornecedor          = h.sq_pessoa)
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_catalogo      is null or (p_catalogo      is not null and a.exibe_catalogo   = p_catalogo))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo))
            and (p_valida        is null or (p_valida        is not null and ((p_valida         = 'S' and g.fim>=sysdate-1) or
                                                                              (p_valida         = 'N' and g.fim<sysdate)
                                                                             )
                                            )
                )
            and (p_item          is null or (p_item          is not null and g.sq_item_fornecedor = p_item));
   ElsIf p_restricao = 'PESQSOLIC' Then
      -- Recupera pesquisas de preço vinculadas a uma solicitação
      open p_result for 
         select a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                f.ano_corrente, f.dias_validade_pesquisa, f.dias_aviso_pesquisa, f.percentual_acrescimo,
                g.sq_item_fornecedor, g.sq_solicitacao_item, g.fornecedor, g.valor_unidade, g.valor_item, g.ordem,
                g.dias_validade_proposta, g.inicio, g.fim, g.origem,
                to_char(g.inicio,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inicio,
                to_char(g.fim,'dd/mm/yyyy, hh24:mi:ss') as phpdt_fim,
                g.fim-f.dias_aviso_pesquisa as aviso,
                g.fabricante, g.marca_modelo, g.embalagem, g.fator_embalagem,
                case g.origem when 'SA' then 'ARP externa' when 'SG' then 'Governo' when 'SF' then 'Site comercial' else 'Proposta fornecedor' end as nm_origem,
                h.nome as nm_fornecedor, h.nome_resumido as nm_fornecedor_res
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                inner     join cl_item_fornecedor  g  on (a.sq_material         = g.sq_material and
                                                          g.pesquisa            = 'S'
                                                         )
                  inner   join co_pessoa           h  on (g.fornecedor          = h.sq_pessoa),
                siw_solicitacao                    i
                inner     join cl_solicitacao      j  on (i.sq_siw_solicitacao  = j.sq_siw_solicitacao)
          where a.cliente            = p_cliente
            and a.sq_material        = p_chave
            and i.sq_siw_solicitacao = p_item
            and a.sq_material        in (select sq_material from cl_solicitacao_item where sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (p_numero_ata        is null or (p_numero_ata    is not null and i.codigo_interno   = p_numero_ata))
            and ((p_valida           is null and g.fim >= trunc(i.inclusao) and g.inicio <= coalesce(i.conclusao,trunc(sysdate))) or 
                 (p_valida           is not null and ((p_valida = 'S' and g.fim>=trunc(sysdate)) or
                                                      (p_valida = 'N' and g.fim<trunc(sysdate))
                                                     )
                 )
                );
   Elsif p_restricao = 'PEDMAT' Then
      -- Recupera pesquisas de preço vinculadas a uma solicitação
      open p_result for 
         select a.sq_material, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.fator_embalagem
           from cl_material                          a
                inner       join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner       join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner       join mt_entrada_item     e  on (a.sq_material         = e.sq_material)
                  inner     join mt_estoque_item     f  on (e.sq_entrada_item     = f.sq_entrada_item and
                                                            f.saldo_atual         > 0
                                                           )
                    inner   join mt_estoque          g  on (f.sq_estoque          = g.sq_estoque)
                      inner join mt_saida            j  on (g.sq_almoxarifado     = j.sq_almoxarifado and
                                                            j.sq_siw_solicitacao  = p_chave
                                                           )
                      left  join mt_saida_item       h  on (j.sq_mtsaida          = h.sq_mtsaida and
                                                            a.sq_material         = h.sq_material
                                                           )
          where a.cliente            = p_cliente
            and a.exibe_catalogo     = 'S'
            and a.ativo              = 'S'
            and h.sq_saida_item      is null;
   Elsif p_restricao = 'PESQUISA' Then
      -- Recupera pesquisas de preço de materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.qtd
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                left      join (select x.sq_material, sum(x.quantidade) qtd, y.arp
                                  from cl_solicitacao_item        x
                                       inner join cl_solicitacao  y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                       inner join siw_solicitacao w on (x.sq_siw_solicitacao = w.sq_siw_solicitacao)
                                       inner join siw_tramite     z on (w.sq_siw_tramite     = z.sq_siw_tramite and
                                                                        'AT'                 = z.sigla)
                                group by x.sq_material, y.arp
                               )                   g  on (a.sq_material         = g.sq_material)
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_catalogo      is null or (p_catalogo      is not null and a.exibe_catalogo   = p_catalogo))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo))
            and (p_arp           is null or (p_arp           is not null and g.sq_material is not null and g.arp = p_arp))
            and ((p_aviso        = 'S' and coalesce(a.pesquisa_validade-f.dias_aviso_pesquisa,sysdate-1)<sysdate and a.pesquisa_validade>=sysdate-1) or
                 (p_invalida     = 'S' and coalesce(a.pesquisa_validade,sysdate-1)<sysdate-1) or
                 (p_valida       = 'S' and coalesce(a.pesquisa_validade,sysdate-1)>=sysdate-1 and coalesce(a.pesquisa_validade-f.dias_aviso_pesquisa,sysdate-1)>sysdate) or
                 (p_branco       = 'S' and a.pesquisa_validade is null)
                )
            and (p_aviso||p_valida||p_invalida||p_branco     is not null);
   Elsif p_restricao = 'RELATORIO' Then
      -- Recupera materiais e serviços que constam de ARP
      open p_result for 
         select a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, 
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 2 then 'Alimento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                f.percentual_acrescimo,
                x.ordem as nr_item_ata, 
                x.quantidade, x.quantidade_autorizada as qtd_comprada,
                x1.valor_unidade, x1.valor_item, x1.origem, x1.fator_embalagem,
                case x1.origem when 'SA' then 'ARP externa' when 'SG' then 'Governo' when 'SF' then 'Site comercial' else 'Proposta fornecedor' end as nm_origem,
                x2.sq_pessoa as sq_detentor_ata, x2.nome_resumido as nm_detentor_ata, 
                w.sq_siw_solicitacao, w.codigo_interno as numero_ata, w.inicio, w.fim,
                w1.sigla,
                w2.objeto, w2.aviso_prox_conc, 
                cast(w2.fim as date)-cast(w2.dias_aviso as integer) as aviso,
                w3.sigla as sg_tramite,
                ((1 - (a.pesquisa_preco_medio/x1.valor_unidade)) * 100) as variacao_valor
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                inner     join cl_solicitacao_item x  on (a.sq_material         = x.sq_material)
                  inner   join cl_item_fornecedor  x1 on (x.sq_solicitacao_item = x1.sq_solicitacao_item)
                    inner join co_pessoa           x2 on (x1.fornecedor         = x2.sq_pessoa)
                  inner   join siw_solicitacao     w  on (x.sq_siw_solicitacao  = w.sq_siw_solicitacao)
                    inner join siw_menu            w1 on (w.sq_menu             = w1.sq_menu and w1.sigla = 'GCZCAD')
                    inner join ac_acordo           w2 on (w.sq_siw_solicitacao  = w2.sq_siw_solicitacao)
                    inner join siw_tramite         w3 on (w.sq_siw_tramite      = w3.sq_siw_tramite and
                                                          w3.sigla              <> 'CA'
                                                         )
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_catalogo      is null or (p_catalogo      is not null and a.exibe_catalogo   = p_catalogo))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo))
            and (p_numero_ata    is null or (p_numero_ata    is not null and w.codigo_interno   = p_numero_ata))
            and (p_acrescimo     is null or (p_acrescimo     is not null and ((1 - (a.pesquisa_preco_medio/x1.valor_unidade)) * 100) > f.percentual_acrescimo))
            and ((p_ata_aviso    = 'S' and w2.aviso_prox_conc = 'S' and trunc(sysdate) between cast(w2.fim as date)-cast(w2.dias_aviso as integer) and cast(w2.fim as date)) or
                 (p_ata_invalida = 'S' and trunc(sysdate) > cast(w2.fim as date)) or
                 (p_ata_valida   = 'S' and trunc(sysdate) < case when w2.aviso_prox_conc = 'N' then cast(w2.fim as date) else cast(w2.fim as date)-cast(w2.dias_aviso as integer) end)
                )
            and ((p_aviso        = 'S' and coalesce(a.pesquisa_validade-f.dias_aviso_pesquisa,sysdate-1)<sysdate and a.pesquisa_validade>=sysdate-1) or
                 (p_invalida     = 'S' and coalesce(a.pesquisa_validade,sysdate-1)<sysdate-1) or
                 (p_valida       = 'S' and coalesce(a.pesquisa_validade,sysdate-1)>=sysdate-1 and coalesce(a.pesquisa_validade-f.dias_aviso_pesquisa,sysdate-1)>sysdate) or
                 (p_branco       = 'S' and a.pesquisa_validade is null)
                )
            and (p_aviso||p_valida||p_invalida||p_branco     is not null);
   End If;
end sp_getMatServ;
/
