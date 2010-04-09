create or replace procedure SP_GetSolicLog
   (p_chave     in number,
    p_tipo      in number   default null, -- 0: encaminhamentos; 1: anotaçoes; 2: versões
    p_restricao in varchar2,
    p_result    out sys_refcursor) is

   w_modulo siw_modulo.sigla%type;
   w_opcao  siw_menu.sigla%type;
   w_reg    number(4);
begin
   -- Verifica se a solicitação existe
   select count(sq_siw_solicitacao) into w_reg from siw_solicitacao where sq_siw_solicitacao = coalesce(p_chave,0);
   If w_reg = 0 Then
      -- Se não existir, aborta a execução
      return;
   End If;
   
   -- Recupera o módulo da solicitacao para decidir onde buscará os interessados
   select c.sigla, b.sigla into w_modulo, w_opcao
     from siw_solicitacao         a
          inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
            inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
    where a.sq_siw_solicitacao = p_chave;
      
   If w_modulo = 'DM' or w_opcao = 'GDPCAD' or w_opcao = 'ORPCAD' or w_opcao = 'ISTCAD' Then -- Se for o módulo de demandas
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select h.sq_demanda_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_demanda_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                     a
                   inner      join siw_tramite       a1 on (a.sq_siw_tramite    = a1.sq_siw_tramite)
                   inner      join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join gd_demanda_log    h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left     join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left       join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left     join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_demanda_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from gd_demanda_log                     b 
                   left outer join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                   inner      join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                   inner      join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                     left outer join siw_arquivo      k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PR' or w_modulo = 'OR' or w_modulo = 'IS' Then -- Se for o módulo de projetos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um projeto
         open p_result for 
            select h.sq_projeto_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_projeto_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   coalesce(k.sq_siw_arquivo, m.sq_siw_arquivo) as sq_siw_arquivo,
                   coalesce(k.caminho,m.caminho) as caminho,
                   coalesce(k.tipo,m.tipo) as tipo,
                   coalesce(k.tamanho,m.tamanho) as tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                      a
                   inner      join siw_tramite        a1 on (a.sq_siw_tramite    = a1.sq_siw_tramite)
                   inner      join co_pessoa          c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite        e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao    g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite        f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join pj_projeto_log     h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left     join co_pessoa          i  on (h.destinatario       = i.sq_pessoa)
                     left     join pj_projeto_log_arq j  on (h.sq_projeto_log     = j.sq_projeto_log)
                       left   join siw_arquivo        k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
                   left       join siw_solic_log_arq  l  on (a.sq_siw_solic_log   = l.sq_siw_solic_log)
                     left     join siw_arquivo        m  on (l.sq_siw_arquivo     = m.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_projeto_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pj_projeto_log                        b 
                      left outer join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pj_projeto_log_arq j on (b.sq_projeto_log     = j.sq_projeto_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PE' Then -- Se for o módulo de planejamento
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um projeto
         open p_result for 
            select h.sq_programa_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   coalesce(k.sq_siw_arquivo, m.sq_siw_arquivo) as sq_siw_arquivo,
                   coalesce(k.caminho,m.caminho) as caminho,
                   coalesce(k.tipo,m.tipo) as tipo,
                   coalesce(k.tamanho,m.tamanho) as tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                         a
                   inner      join siw_tramite           a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner      join co_pessoa             c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite           e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao       g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite           f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join pe_programa_log       h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left     join co_pessoa             i  on (h.destinatario       = i.sq_pessoa)
                     left     join pe_programa_log_arq   j  on (h.sq_programa_log    = j.sq_programa_log)
                       left   join siw_arquivo           k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
                   left       join siw_solic_log_arq     l  on (a.sq_siw_solic_log   = l.sq_siw_solic_log)
                     left     join siw_arquivo           m  on (l.sq_siw_arquivo     = m.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_programa_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pe_programa_log                        b 
                      left       join co_pessoa           d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa           c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao     g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite         f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left       join pe_programa_log_arq j on (b.sq_programa_log    = j.sq_programa_log)
                         left    join siw_arquivo         k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'AC' or substr(w_opcao,1,3)='GCZ' Then -- Se for o módulo de acordos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select h.sq_acordo_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_acordo_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   h1.sq_tipo_log, h1.nome as nm_tipo_log, h1.sigla as sg_tipo_log,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data,
                   null as tipo_anotacao
              from siw_solic_log                       a
                   inner        join siw_tramite       a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner        join co_pessoa         c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log     h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join siw_tipo_log      h1 on (h.sq_tipo_log        = h1.sq_tipo_log)
                     left outer join co_pessoa         i  on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_acordo_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   b1.sq_tipo_log, b1.nome as nm_tipo_log, b1.sigla as sg_tipo_log,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data,
                   case when despacho is null then 'Anotação' else b.observacao end as tipo_anotacao
              from ac_acordo_log                       b 
                   left outer join siw_tipo_log        b1 on (b.sq_tipo_log        = b1.sq_tipo_log)
                   left outer   join co_pessoa         d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa         c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao   g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log_arq j on (b.sq_acordo_log      = j.sq_acordo_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'FN' Then -- Se for o módulo financeiro
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select h.sq_lancamento_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_lancamento_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                       a
                   inner        join siw_tramite       a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner        join co_pessoa         c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i  on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_lancamento_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from fn_lancamento_log                       b 
                   left outer   join co_pessoa             d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa             c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao       g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite           f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log_arq j on (b.sq_lancamento_log  = j.sq_lancamento_log)
                     left outer join siw_arquivo           k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PD' Then -- Se for o módulo de viagens
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_demanda_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   case when h.sq_demanda_log is null then a.observacao else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho end as despacho,
                   'TRAMITACAO' as origem,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite, 
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                       a
                   inner        join siw_tramite       a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner        join co_pessoa         c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join gd_demanda_log    h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i  on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_demanda_log as chave_log, b.sq_siw_solic_log, 0, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   'ANOTACAO' origem,
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from gd_demanda_log                           b
                   left outer     join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                   inner          join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                   inner          join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner        join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer     join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                     left outer   join siw_arquivo        k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (p_tipo is null or (p_tipo is not null and ((p_tipo = 0 and b.destinatario is not null) or
                                                               (p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = p_chave;
      End If;      
   Elsif w_modulo in ('SR','CO') Then -- Se for o módulo de recursos logísticos ou de compras
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select a.sq_siw_solic_log, a.sq_siw_tramite,a.data, a.observacao as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   f.nome as fase, f.descricao,
                   e.nome as tramite, 
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                     a
                   inner        join siw_tramite     a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner      join co_pessoa         c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo     k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão' and substr(a.observacao,1,9) <> 'Anotação:') or
                                                               (p_tipo =  1 and substr(a.observacao,1,9) = 'Anotação:') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   );
      End If;
   Elsif w_modulo = 'PE' Then -- Se for o módulo de planejamento estratégico
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um programa
         open p_result for 
            select h.sq_programa_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log   a
                   inner      join siw_tramite         a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner      join co_pessoa           c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite         e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao     g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite         f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join pe_programa_log     h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left     join co_pessoa           i  on (h.destinatario       = i.sq_pessoa)
                     left     join pe_programa_log_arq j  on (h.sq_programa_log    = j.sq_programa_log)
                       left   join siw_arquivo         k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_programa_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   null as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pe_programa_log                     b 
                   left       join co_pessoa           d on (b.destinatario       = d.sq_pessoa)
                   inner      join co_pessoa           c on (b.cadastrador        = c.sq_pessoa)
                   inner      join siw_solicitacao     g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite         f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join pe_programa_log_arq j on (b.sq_programa_log    = j.sq_programa_log)
                      left outer join siw_arquivo      k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_opcao = 'PADCAD' Then -- Se for o registro de protocolos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um programa
         open p_result for 
            select h.sq_documento_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   coalesce(m.nome,a.observacao)||case when h.resumo is not null then ': '||h.resumo else '' end as despacho,
                   case when h.sq_documento_log is null then a1.sq_pessoa else n.sq_pessoa end as sq_pessoa_resp,
                   case when h.sq_documento_log is null then a1.nome_resumido else n.nome_resumido end as nm_pessoa_resp,
                   h.recebedor, h1.nome_resumido as nm_recebedor,
                   case when h.sq_documento_log is null 
                        then case when instr(upper(a.observacao),'AUTUA')>0             then 'AUTUAÇÃO DE PROCESSO'
                                  when instr(upper(a.observacao),'*** NOVA VERSÃO')>0   then 'ALTERAÇÃO DE DADOS'
                                  when instr(upper(a.observacao),'RENUMERAÇÃO')>0       then 'RENUMERAÇÃO'
                                  when instr(upper(a.observacao),'ANEXA')>0             then 'ANEXAÇÃO'
                                  when instr(upper(a.observacao),'APENSA')>0            then 'APENSAÇÃO'
                                  when instr(upper(a.observacao),'DESM')>0              then 'DESMEMBRAMENTO'
                                  when instr(upper(a.observacao),'INDICAÇÃO')>0         then 'CLASSIFICAÇÃO'
                                  when instr(upper(a.observacao),'ARQ')>0               then 'ARQUIVAMENTO'
                                  when instr(upper(a.observacao),'ENVIO EXTERNO')>0     then 'ENVIO EXTERNO'
                                  else 'REGISTRO' 
                             end
                        else case f.sigla
                                  when 'AS' then 'ARQUIVAMENTO'
                                  when 'AT' then 'ARQUIVAMENTO CENTRAL'
                                  when 'EL' then 'ELIMINACAO'
                                  else 'TRÂMITE ORIGINAL' 
                             end
                   end as origem,
                   c.sq_unidade as sq_registro, c.sigla nm_registro, l1.sq_unidade as sq_origem, l1.sigla as nm_origem,
                   case when i.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when i.sq_pessoa is not null then i.sq_pessoa     else l.sq_unidade end as sq_destinatario,
                   case when i.sq_pessoa is not null then i.nome_resumido else l.sigla      end as nm_destinatario,
                   e.nome as tramite,
                   f.nome as fase, f.descricao,
                   h.interno, h.pessoa_externa,h.unidade_externa,
                   to_char(h.envio, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_envio, to_char(h.recebimento, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_receb,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                    a
                   inner     join co_pessoa         a1 on (a.sq_pessoa          = a1.sq_pessoa)
                   inner     join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner     join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner   join eo_unidade        c  on (g.sq_unidade         = c.sq_unidade)
                     inner   join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left      join pa_documento_log  h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left    join co_pessoa         h1 on (h.recebedor          = h1.sq_pessoa)
                     left    join eo_unidade        l  on (h.unidade_destino    = l.sq_unidade)
                     left    join eo_unidade        l1 on (h.unidade_origem     = l1.sq_unidade)
                     left    join pa_tipo_despacho  m  on (h.sq_tipo_despacho   = m.sq_tipo_despacho)
                     left    join co_pessoa         i  on (h.pessoa_destino     = i.sq_pessoa)
                     left    join co_pessoa         n  on (h.cadastrador        = n.sq_pessoa)
                   left      join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left    join siw_arquivo       k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo),
                  pa_parametro                      p
             where a.sq_siw_solicitacao = p_chave
               and p.cliente            = a1.sq_pessoa_pai
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_documento_log as chave_log, b.sq_siw_solic_log, 0, b.data_inclusao, 
                   case when b.resumo is null
                        then b1.nome
                        else case b1.sq_tipo_despacho 
                                  when b3.despacho_desmembrar 
                                  then b1.nome||' PROTOCOLO(S): '||b.resumo 
                                  else b1.nome||': '||b.resumo 
                             end
                   end as despacho,
                   b2.sq_pessoa as sq_pessoa_resp, b2.nome_resumido as nm_pessoa_resp,
                   b.recebedor, b4.nome_resumido as nm_recebedor,
                   'TRAMITAÇÃO' as origem,
                   e.sq_unidade as sq_registro, e.sigla as nm_registro, e.sq_unidade as sq_origem, e.sigla as nm_origem,
                   case when d.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when d.sq_pessoa is not null then d.sq_pessoa     else h.sq_unidade end as sq_destinatario,
                   case when d.sq_pessoa is not null then d.nome_resumido else h.sigla      end as nm_destinatario,
                   f.nome as tramite, f.nome as fase, f.descricao,
                   b.interno, b.pessoa_externa, b.unidade_externa,
                   to_char(b.envio, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_envio, to_char(b.recebimento, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_receb,
                   null as sq_siw_arquivo, null as caminho, null as tipo, null as tamanho, 
                   to_char(coalesce(b.recebimento, b.envio), 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pa_documento_log                   b
                   inner    join pa_tipo_despacho     b1 on (b.sq_tipo_despacho   = b1.sq_tipo_despacho)
                   inner    join co_pessoa            b2 on (b.cadastrador        = b2.sq_pessoa)
                     inner  join pa_parametro         b3 on (b2.sq_pessoa_pai     = b3.cliente)
                   left     join co_pessoa            b4 on (b.recebedor          = b4.sq_pessoa)
                   left     join co_pessoa            d  on (b.pessoa_destino     = d.sq_pessoa)
                   left     join eo_unidade           e  on (b.unidade_origem     = e.sq_unidade)
                   left     join eo_unidade           h  on (b.unidade_destino    = h.sq_unidade)
                   inner    join co_pessoa            c  on (b.cadastrador        = c.sq_pessoa)
                   inner    join siw_solicitacao      g  on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner  join siw_tramite          f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and (p_tipo <>  2)));
      End If;
   Elsif w_modulo = 'PA' Then -- Se for o módulo de protocolo
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when substr(a.observacao,1,9) = 'Anotação:' then substr(a.observacao,13) 
                        else a.observacao 
                   end as despacho,
                   a1.nome as nm_tramite_log,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   f.nome as fase, f.descricao,
                   e.nome as tramite, 
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                     a
                   inner        join siw_tramite     a1 on (a.sq_siw_tramite     = a1.sq_siw_tramite)
                   inner      join co_pessoa         c  on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo     k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
               and (p_tipo is null or (p_tipo is not null and ((p_tipo =  0 and a.observacao <> '*** Nova versão' and substr(a.observacao,1,9) <> 'Anotação:') or
                                                               (p_tipo =  1 and substr(a.observacao,1,9) = 'Anotação:') or
                                                               (p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   );
      End If;
   End If;
End SP_GetSolicLog;
/
