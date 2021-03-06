create procedure dbo.SP_GetSolicLog
   (@p_chave     int,
    @p_tipo      int=null, -- 0: encaminhamentos; 1: anotaçoes; 2: versões
    @p_restricao varchar(50)) as
begin

   Declare @w_modulo varchar(10)
   Declare @w_opcao  varchar(20)
   Declare @w_reg    int

   -- Verifica se a solicitação existe
   select @w_reg = count(sq_siw_solicitacao) from siw_solicitacao where sq_siw_solicitacao = coalesce(@p_chave,0);
   If @w_reg = 0 return;

   -- Recupera o módulo da solicitacao para decidir onde buscará os interessados
   select @w_modulo = c.sigla, @w_opcao = b.sigla
     from siw_solicitacao         a
          inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
            inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
      and a.sq_siw_solicitacao = @p_chave
      
   If @w_modulo = 'DM' Begin -- Se for o módulo de demandas
      If @p_restricao = 'LISTA'
         -- Recupera os encaminhamentos de uma demanda
            select h.sq_demanda_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_demanda_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                     a
                   inner      join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left       join gd_demanda_log    h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left     join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left       join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left     join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_demanda_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from gd_demanda_log                     b 
                   left outer join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                   inner      join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                   inner      join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                     left outer join siw_arquivo      k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
   End Else If @w_modulo = 'PR' or @w_modulo = 'OR' or @w_modulo = 'IS' Begin -- Se for o módulo de projetos
      If @p_restricao = 'LISTA'
         -- Recupera os encaminhamentos de um projeto
            select h.sq_projeto_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_projeto_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
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
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                         a
                      inner      join co_pessoa          c on (a.sq_pessoa          = c.sq_pessoa)
                      inner      join siw_tramite        e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                      inner      join siw_solicitacao    g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left       join pj_projeto_log     h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                        left     join co_pessoa          i on (h.destinatario       = i.sq_pessoa)
                        left     join pj_projeto_log_arq j on (h.sq_projeto_log     = j.sq_projeto_log)
                          left   join siw_arquivo        k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
                      left       join siw_solic_log_arq  l on (a.sq_siw_solic_log   = l.sq_siw_solic_log)
                        left     join siw_arquivo        m on (l.sq_siw_arquivo     = m.sq_siw_arquivo)
                          
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_projeto_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pj_projeto_log                        b 
                      left outer join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pj_projeto_log_arq j on (b.sq_projeto_log     = j.sq_projeto_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
   End Else If @w_modulo = 'PE' Begin -- Se for o módulo de planejamento
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de um projeto
            select h.sq_programa_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
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
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                            a
                      inner      join co_pessoa             c on (a.sq_pessoa          = c.sq_pessoa)
                      inner      join siw_tramite           e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                      inner      join siw_solicitacao       g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite           f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left       join pe_programa_log       h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                        left     join co_pessoa             i on (h.destinatario       = i.sq_pessoa)
                        left     join pe_programa_log_arq   j on (h.sq_programa_log    = j.sq_programa_log)
                          left   join siw_arquivo           k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
                      left       join siw_solic_log_arq     l on (a.sq_siw_solic_log   = l.sq_siw_solic_log)
                        left     join siw_arquivo           m on (l.sq_siw_arquivo     = m.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_programa_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pe_programa_log                        b 
                      left       join co_pessoa           d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa           c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao     g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite         f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left       join pe_programa_log_arq j on (b.sq_programa_log    = j.sq_programa_log)
                         left    join siw_arquivo         k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
      End
   End Else If @w_modulo = 'AC' or substring(@w_opcao,1,3)='GCZ' Begin -- Se for o módulo de acordos
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de uma demanda
            select h.sq_acordo_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_acordo_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data,
                   null as tipo_anotacao
              from siw_solic_log                       a
                   inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log     h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_acordo_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data,
                   case when despacho is null then 'Anotação' else b.observacao end as tipo_anotacao
              from ac_acordo_log                       b 
                   left outer   join co_pessoa         d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa         c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao   g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log_arq j on (b.sq_acordo_log      = j.sq_acordo_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
      End
   End Else If @w_modulo = 'FN' Begin -- Se for o módulo financeiro
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de uma demanda
            select h.sq_lancamento_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_lancamento_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                       a
                   inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_lancamento_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from fn_lancamento_log                       b 
                   left outer   join co_pessoa             d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa             c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao       g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite           f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log_arq j on (b.sq_lancamento_log  = j.sq_lancamento_log)
                     left outer join siw_arquivo           k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
      End
   End Else If @w_modulo = 'PD' Begin -- Se for o módulo de viagens
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de uma demanda
            select h.sq_demanda_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   case when h.sq_demanda_log is null then a.observacao else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho end as despacho,
                   'TRAMITACAO' as origem,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite, 
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                       a
                   inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join gd_demanda_log    h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   )
            UNION
            select b.sq_demanda_log as chave_log, b.sq_siw_solic_log, 0, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   'ANOTACAO' origem,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from gd_demanda_log                           b
                   left outer     join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                   inner          join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                   inner          join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner        join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer     join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                     left outer   join siw_arquivo        k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo = 0 and b.destinatario is not null) or
                                                               (@p_tipo = 1 and b.destinatario is null)
                                                              )
                                      )
                   )
               and b.sq_siw_solicitacao = @p_chave;
      End      
   End Else If @w_modulo in ('SR','CO') Begin -- Se for o módulo de recursos logísticos ou de compras
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de uma demanda
            select a.sq_siw_solic_log, a.sq_siw_tramite,a.data, a.observacao as despacho,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   f.nome as fase, f.descricao,
                   e.nome as tramite, 
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                     a
                   inner      join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo     k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
               and (@p_tipo is null or (@p_tipo is not null and ((@p_tipo =  0 and a.observacao <> '*** Nova versão') or
                                                               (@p_tipo =  2 and a.observacao =  '*** Nova versão')
                                                              )
                                      )
                   );
      End
   End Else If @w_modulo = 'PE' Begin -- Se for o módulo de planejamento estratégico
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de um programa
            select h.sq_programa_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao+char(13)+char(10)+'DESPACHO: '+char(13)+char(10)+h.despacho
                      end as despacho,
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   i.nome_resumido as destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.descricao,
                   e.nome as tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log   a
                    inner      join co_pessoa       c on (a.sq_pessoa          = c.sq_pessoa)
                    inner      join siw_tramite     e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                    inner      join siw_solicitacao g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                      inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                    left outer join pe_programa_log  h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                      left outer join co_pessoa     i on (h.destinatario       = i.sq_pessoa)
                      left outer join pe_programa_log_arq j on (h.sq_programa_log  = j.sq_programa_log)
                        left outer join siw_arquivo      k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
            UNION
            select b.sq_programa_log as chave_log, b.sq_siw_solic_log, null, b.data_inclusao,  coalesce(b.despacho, b.observacao),
                   c.nome_resumido as responsavel,
                   c.sq_pessoa,
                   d.nome_resumido as destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome as fase, f.nome as tramite, f.descricao,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pe_programa_log  b 
                      left outer join co_pessoa       d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa       c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pe_programa_log_arq j on (b.sq_programa_log  = j.sq_programa_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = @p_chave;
      End
   End Else If @w_modulo = 'PA' Begin -- Se for o módulo de protocolo e arquivo
      If @p_restricao = 'LISTA' Begin
         -- Recupera os encaminhamentos de um programa
            select h.sq_documento_log as chave_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   coalesce(m.nome,a.observacao) as despacho,
                   case when h.sq_documento_log is null then a1.sq_pessoa else n.sq_pessoa end as sq_pessoa_resp,
                   case when h.sq_documento_log is null then a1.nome_resumido else n.nome_resumido end as nm_pessoa_resp,
                   case when h.sq_documento_log is null 
                        then case when charIndex('AUTUA',upper(a.observacao))>0       then 'AUTUAÇÃO DE PROCESSO'
                                  when charIndex('RENUMERAÇÃO',upper(a.observacao))>0 then 'RENUMERAÇÃO'
                                  else 'REGISTRO' 
                             end
                        else 'TRAMITE ORIGINAL' 
                   end as origem,
                   c.sq_unidade as sq_registro, c.sigla nm_registro, l1.sq_unidade as sq_origem, l1.sigla as nm_origem,
                   case when i.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when i.sq_pessoa is not null then i.sq_pessoa     else l.sq_unidade end as sq_destinatario,
                   case when i.sq_pessoa is not null then i.nome_resumido else l.sigla      end as nm_destinatario,
                   e.nome as tramite,
                   f.nome as fase, f.descricao,
                   h.interno, h.pessoa_externa,h.unidade_externa,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   dbo.to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw_solic_log                    a
                   inner     join co_pessoa         a1 on (a.sq_pessoa          = a1.sq_pessoa)
                   inner     join siw_tramite       e  on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner     join siw_solicitacao   g  on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner   join eo_unidade        c  on (g.sq_unidade         = c.sq_unidade)
                     inner   join siw_tramite       f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left      join pa_documento_log  h  on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left    join eo_unidade        l  on (h.unidade_destino    = l.sq_unidade)
                     left    join eo_unidade        l1 on (h.unidade_origem     = l1.sq_unidade)
                     left    join pa_tipo_despacho  m  on (h.sq_tipo_despacho   = m.sq_tipo_despacho)
                     left    join co_pessoa         i  on (h.pessoa_destino     = i.sq_pessoa)
                     left    join co_pessoa         n  on (h.cadastrador        = n.sq_pessoa)
                   left      join siw_solic_log_arq j  on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left    join siw_arquivo       k  on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = @p_chave
            UNION
            select b.sq_documento_log as chave_log, b.sq_siw_solic_log, 0, b.data_inclusao, 
                   b1.nome as despacho,
                   b2.sq_pessoa as sq_pessoa_resp, b2.nome_resumido as nm_pessoa_resp,
                   'TRAMITACAO' as origem,
                   e.sq_unidade as sq_registro, e.sigla as nm_registro, e.sq_unidade as sq_origem, e.sigla as nm_origem,
                   case when d.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when d.sq_pessoa is not null then d.sq_pessoa     else h.sq_unidade end as sq_destinatario,
                   case when d.sq_pessoa is not null then d.nome_resumido else h.sigla      end as nm_destinatario,
                   f.nome as tramite, f.nome as fase, f.descricao,
                   b.interno, b.pessoa_externa, b.unidade_externa,
                   null as sq_siw_arquivo, null as caminho, null as tipo, null as tamanho, 
                   dbo.to_char(coalesce(b.recebimento, b.envio), 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from pa_documento_log                   b
                   inner    join pa_tipo_despacho     b1 on (b.sq_tipo_despacho   = b1.sq_tipo_despacho)
                   inner    join co_pessoa            b2 on (b.cadastrador        = b2.sq_pessoa)
                   left     join co_pessoa            d  on (b.pessoa_destino     = d.sq_pessoa)
                   left     join eo_unidade           e  on (b.unidade_origem     = e.sq_unidade)
                   left     join eo_unidade           h  on (b.unidade_destino    = h.sq_unidade)
                   inner    join co_pessoa            c  on (b.cadastrador        = c.sq_pessoa)
                   inner    join siw_solicitacao      g  on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner  join siw_tramite          f  on (g.sq_siw_tramite     = f.sq_siw_tramite)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = @p_chave;
      End
   End
End
