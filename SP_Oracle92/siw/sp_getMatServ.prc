create or replace procedure sp_getMatServ
   (p_cliente       in number,
    p_usuario       in number,
    p_chave         in number    default null,
    p_tipo_material in number    default null,
    p_sq_cc         in number    default null,
    p_codigo        in varchar2  default null,
    p_nome          in varchar   default null,
    p_ativo         in varchar2  default null,
    p_restricao     in varchar2  default null,
    p_result        out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'ALOCACAO' or p_restricao = 'VINCULACAO' or p_restricao = 'EDICAOT' or p_restricao = 'EDICAOP' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, a.sq_cc,
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (a.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_sq_cc         is null or (p_sq_cc         is not null and a.sq_cc            = p_sq_cc))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo));
   Elsif p_restricao = 'COMPRA' Then
      -- Recupera materiais e serviços não vinculados a compra
      open p_result for 
         select /*+ ordered */ a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, a.sq_cc,
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                a.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (a.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
          where a.cliente         = p_cliente
            and a.sq_material    not in (select x.sq_material from cl_solicitacao_item x where x.sq_siw_solicitacao = p_chave)
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_sq_cc         is null or (p_sq_cc         is not null and a.sq_cc            = p_sq_cc))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo));            
   ElsIf p_restricao = 'EXISTE' Then
      -- Verifica se o nome ou a codigo do material ou serviço já foi inserida
      open p_result for 
         select count(a.sq_material) as existe
           from cl_material  a
          where a.cliente        = p_cliente
            and sq_material      <> coalesce(p_chave,0)
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'));
   ElsIf p_restricao = 'PESQMAT' Then
      -- Verifica se o nome ou a codigo do material ou serviço já foi inserida
      open p_result for 
         select a.sq_material as chave, a.cliente, a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                a.exibe_catalogo, a.vida_util, a.ativo, a.sq_cc,
                a.pesquisa_preco_menor, a.pesquisa_preco_maior, a.pesquisa_preco_medio,
                a.pesquisa_data, a.pesquisa_validade, 
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                f.ano_corrente, f.dias_validade_pesquisa, f.dias_aviso_pesquisa, f.percentual_acrescimo,
                g.sq_item_fornecedor, g.sq_solicitacao_item, g.fornecedor, g.valor_unidade, g.valor_item, g.ordem,
                to_char(g.inicio,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inicio,
                to_char(g.fim,'dd/mm/yyyy, hh24:mi:ss') as phpdt_fim,
                g.fim-f.dias_aviso_pesquisa as aviso,
                h.nome as nm_fornecedor, h.nome_resumido as nm_fornecedor_res
           from cl_material                        a
                inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (a.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (a.cliente             = f.cliente)
                inner     join cl_item_fornecedor  g  on (a.sq_material         = g.sq_material)
                  inner   join co_pessoa           h  on (g.fornecedor          = h.sq_pessoa)
          where a.cliente         = p_cliente
            and (p_chave         is null or (p_chave         is not null and a.sq_material      = p_chave))
            and (p_tipo_material is null or (p_tipo_material is not null and a.sq_tipo_material = p_tipo_material))
            and (p_sq_cc         is null or (p_sq_cc         is not null and a.sq_cc            = p_sq_cc))
            and (p_codigo        is null or (p_codigo        is not null and a.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(a.nome)    like '%'||acentos(p_nome)||'%'))
            and (p_ativo         is null or (p_ativo         is not null and a.ativo            = p_ativo));
   End If;
end sp_getMatServ;
/
