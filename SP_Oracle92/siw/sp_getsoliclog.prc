create or replace procedure SP_GetSolicLog
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is

   w_modulo siw_modulo.sigla%type;
   w_opcao  siw_menu.sigla%type;
begin
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
            select h.sq_demanda_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_demanda_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                     a
                   inner      join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join gd_demanda_log    h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa       i on (h.destinatario       = i.sq_pessoa)
                   left outer join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo     k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_demanda_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from gd_demanda_log                     b 
                   left outer join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                   inner      join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                   inner      join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                     left outer join siw_arquivo      k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PR' or w_modulo = 'OR' or w_modulo = 'IS' Then -- Se for o módulo de projetos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um projeto
         open p_result for 
            select h.sq_projeto_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_projeto_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log   a
                      inner      join co_pessoa       c on (a.sq_pessoa          = c.sq_pessoa)
                      inner      join siw_tramite     e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                      inner      join siw_solicitacao g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pj_projeto_log  h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                        left outer join co_pessoa     i on (h.destinatario       = i.sq_pessoa)
                        left outer join pj_projeto_log_arq j on (h.sq_projeto_log  = j.sq_projeto_log)
                          left outer join siw_arquivo      k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_projeto_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from pj_projeto_log  b 
                      left outer join co_pessoa       d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa       c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pj_projeto_log_arq j on (b.sq_projeto_log  = j.sq_projeto_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PE' Then -- Se for o módulo de planejamento
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um projeto
         open p_result for 
            select h.sq_programa_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log   a
                      inner      join co_pessoa       c on (a.sq_pessoa          = c.sq_pessoa)
                      inner      join siw_tramite     e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                      inner      join siw_solicitacao g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pe_programa_log  h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                        left outer join co_pessoa     i on (h.destinatario       = i.sq_pessoa)
                        left outer join pe_programa_log_arq j on (h.sq_programa_log  = j.sq_programa_log)
                          left outer join siw_arquivo      k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_programa_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from pe_programa_log  b 
                      left outer join co_pessoa       d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa       c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pe_programa_log_arq j on (b.sq_programa_log  = j.sq_programa_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'AC' Then -- Se for o módulo de acordos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select h.sq_acordo_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_acordo_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                       a
                   inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log     h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_acordo_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from ac_acordo_log                       b 
                   left outer   join co_pessoa         d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa         c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao   g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join ac_acordo_log_arq j on (b.sq_acordo_log      = j.sq_acordo_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'FN' Then -- Se for o módulo financeiro
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select h.sq_lancamento_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_lancamento_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                       a
                   inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                     left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                   left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_lancamento_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from fn_lancamento_log                       b 
                   left outer   join co_pessoa             d on (b.destinatario       = d.sq_pessoa)
                   inner        join co_pessoa             c on (b.cadastrador        = c.sq_pessoa)
                   inner        join siw_solicitacao       g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner      join siw_tramite           f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer   join fn_lancamento_log_arq j on (b.sq_lancamento_log  = j.sq_lancamento_log)
                     left outer join siw_arquivo           k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PD' Then -- Se for o módulo de viagens
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_demanda_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   case when h.sq_demanda_log is null then a.observacao else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho end despacho,
                   'TRAMITACAO' origem,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                        a
                     inner        join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                     inner        join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                     inner        join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                       inner      join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                     left outer   join gd_demanda_log    h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                       left outer join co_pessoa         i on (h.destinatario       = i.sq_pessoa)
                     left outer   join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                       left outer join siw_arquivo       k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_demanda_log, b.sq_siw_solic_log, 0, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   'ANOTACAO' origem,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from gd_demanda_log                           b
                     left outer     join co_pessoa          d on (b.destinatario       = d.sq_pessoa)
                     inner          join co_pessoa          c on (b.cadastrador        = c.sq_pessoa)
                     inner          join siw_solicitacao    g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                       inner        join siw_tramite        f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                     left outer     join gd_demanda_log_arq j on (b.sq_demanda_log     = j.sq_demanda_log)
                       left outer   join siw_arquivo        k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;      
   Elsif w_modulo = 'SR' Then -- Se for o módulo de recursos logísticos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for 
            select a.sq_siw_solic_log, a.sq_siw_tramite,a.data, a.observacao,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                     a
                   inner      join co_pessoa         c on (a.sq_pessoa          = c.sq_pessoa)
                   inner      join siw_tramite       e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                   inner      join siw_solicitacao   g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                     inner    join siw_tramite       f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                   left outer join siw_solic_log_arq j on (a.sq_siw_solic_log   = j.sq_siw_solic_log)
                     left outer join siw_arquivo     k on (j.sq_siw_arquivo     = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PE' Then -- Se for o módulo de planejamento estratégico
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um programa
         open p_result for 
            select h.sq_programa_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   case when h.sq_programa_log is null 
                      then a.observacao 
                      else a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho
                      end despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, 
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log   a
                      inner      join co_pessoa       c on (a.sq_pessoa          = c.sq_pessoa)
                      inner      join siw_tramite     e on (a.sq_siw_tramite     = e.sq_siw_tramite)
                      inner      join siw_solicitacao g on (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pe_programa_log  h on (a.sq_siw_solic_log   = h.sq_siw_solic_log)
                        left outer join co_pessoa     i on (h.destinatario       = i.sq_pessoa)
                        left outer join pe_programa_log_arq j on (h.sq_programa_log  = j.sq_programa_log)
                          left outer join siw_arquivo      k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_programa_log, b.sq_siw_solic_log, null, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from pe_programa_log  b 
                      left outer join co_pessoa       d on (b.destinatario       = d.sq_pessoa)
                      inner      join co_pessoa       c on (b.cadastrador        = c.sq_pessoa)
                      inner      join siw_solicitacao g on (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner    join siw_tramite     f on (g.sq_siw_tramite     = f.sq_siw_tramite)
                      left outer join pe_programa_log_arq j on (b.sq_programa_log  = j.sq_programa_log)
                         left outer join siw_arquivo     k on (j.sq_siw_arquivo  = k.sq_siw_arquivo)
             where b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PA' Then -- Se for o módulo de protocolo e arquivo
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de um programa
         open p_result for 
            select h.sq_documento_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data, 
                   coalesce(m.nome,a.observacao) as despacho,
                   case when h.sq_documento_log is null then a1.sq_pessoa else n.sq_pessoa end as sq_pessoa_resp,
                   case when h.sq_documento_log is null then a1.nome_resumido else n.nome_resumido end as nm_pessoa_resp,
                   case when h.sq_documento_log is null 
                        then case when instr(upper(a.observacao),'AUTUA')>0 then 'AUTUAÇÃO DE PROCESSO' else 'REGISTRO' end
                        else 'TRAMITE ORIGINAL' 
                   end as origem,
                   c.sq_unidade as sq_registro, c.sigla nm_registro, l1.sq_unidade as sq_origem, l1.sigla as nm_origem,
                   case when i.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when i.sq_pessoa is not null then i.sq_pessoa     else l.sq_unidade end as sq_destinatario,
                   case when i.sq_pessoa is not null then i.nome_resumido else l.sigla      end as nm_destinatario,
                   e.nome tramite,
                   f.nome fase,
                   h.interno, h.pessoa_externa,h.unidade_externa,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
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
             where a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_documento_log, b.sq_siw_solic_log, 0, b.data_inclusao, 
                   b1.nome as despacho,
                   b2.sq_pessoa as sq_pessoa_resp, b2.nome_resumido as nm_pessoa_resp,
                   'TRAMITACAO' origem,
                   e.sq_unidade as sq_registro, e.sigla as nm_registro, e.sq_unidade as sq_origem, e.sigla as nm_origem,
                   case when d.sq_pessoa is not null then 'PESSOA'        else 'UNIDADE'    end as tipo_destinatario,
                   case when d.sq_pessoa is not null then d.sq_pessoa     else h.sq_unidade end as sq_destinatario,
                   case when d.sq_pessoa is not null then d.nome_resumido else h.sigla      end as nm_destinatario,
                   f.nome tramite, f.nome fase,
                   b.interno, b.pessoa_externa, b.unidade_externa,
                   null as sq_siw_arquivo, null as caminho, null as tipo, null as tamanho, 
                   to_char(coalesce(b.recebimento, b.envio), 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
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
               and b.sq_siw_solicitacao = p_chave;
      End If;
   End If;
End SP_GetSolicLog;
/
