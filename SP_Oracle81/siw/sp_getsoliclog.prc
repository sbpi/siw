create or replace procedure SP_GetSolicLog
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is

   w_modulo siw_modulo.sigla%type;
   w_opcao  siw_menu.sigla%type;
begin
   -- Recupera o módulo da solicitacao para decidir onde buscará os interessados
   select c.sigla, b.sigla into w_modulo, w_opcao
     from siw_solicitacao         a,
          siw_menu   b,
          siw_modulo c
    where (a.sq_menu   = b.sq_menu)
      and (b.sq_modulo = c.sq_modulo)
      and a.sq_siw_solicitacao = p_chave;

   If w_modulo = 'DM' or w_opcao = 'ORPCAD' or w_opcao = 'ISTCAD' Then -- Se for o módulo de demandas
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_demanda_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   decode(h.sq_demanda_log,null,a.observacao,a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho) despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                     a,
                   co_pessoa         c,
                   siw_tramite       e,
                   siw_solicitacao   g,
                   siw_tramite       f,
                   gd_demanda_log    h,
                   co_pessoa       i,
                   siw_solic_log_arq j,
                   siw_arquivo     k
             where (a.sq_pessoa          = c.sq_pessoa)
               and (a.sq_siw_tramite     = e.sq_siw_tramite)
               and (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (a.sq_siw_solic_log   = h.sq_siw_solic_log (+))
               and (h.destinatario       = i.sq_pessoa (+))
               and (a.sq_siw_solic_log   = j.sq_siw_solic_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_demanda_log, b.sq_siw_solic_log, 0, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from gd_demanda_log                     b,
                   co_pessoa          d,
                   co_pessoa          c,
                   siw_solicitacao    g,
                   siw_tramite        f,
                   gd_demanda_log_arq j,
                   siw_arquivo      k
             where (b.destinatario       = d.sq_pessoa (+))
               and (b.cadastrador        = c.sq_pessoa)
               and (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (b.sq_demanda_log     = j.sq_demanda_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PR' or w_modulo = 'OR' or w_modulo = 'IS' Then -- Se for o módulo de projetos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_projeto_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   decode(h.sq_projeto_log,null,a.observacao,a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho) despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log   a,
                   co_pessoa       c,
                   siw_tramite     e,
                   siw_solicitacao g,
                   siw_tramite     f,
                   pj_projeto_log  h,
                   co_pessoa       i,
                   pj_projeto_log_arq j,
                   siw_arquivo     k
             where (a.sq_pessoa          = c.sq_pessoa)
               and (a.sq_siw_tramite     = e.sq_siw_tramite)
               and (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (a.sq_siw_solic_log   = h.sq_siw_solic_log (+))
               and (h.destinatario       = i.sq_pessoa (+))
               and (h.sq_projeto_log     = j.sq_projeto_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_projeto_log, b.sq_siw_solic_log, 0, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from pj_projeto_log  b,
                   co_pessoa       d,
                   co_pessoa       c,
                   siw_solicitacao g,
                   siw_tramite     f,
                   pj_projeto_log_arq j,
                   siw_arquivo     k
             where (b.destinatario       = d.sq_pessoa (+))
               and (b.cadastrador        = c.sq_pessoa)
               and (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (b.sq_projeto_log     = j.sq_projeto_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'AC' Then -- Se for o módulo de acordos
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_acordo_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   decode(h.sq_acordo_log,null,a.observacao,a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho) despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                       a,
                   co_pessoa         c,
                   siw_tramite       e,
                   siw_solicitacao   g,
                   siw_tramite       f,
                   ac_acordo_log     h,
                   co_pessoa         i,
                   siw_solic_log_arq j,
                   siw_arquivo       k
             where (a.sq_pessoa          = c.sq_pessoa)
               and (a.sq_siw_tramite     = e.sq_siw_tramite)
               and (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (a.sq_siw_solic_log   = h.sq_siw_solic_log (+))
               and (h.destinatario       = i.sq_pessoa (+))
               and (a.sq_siw_solic_log   = j.sq_siw_solic_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_acordo_log, b.sq_siw_solic_log, 0, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from ac_acordo_log                       b,
                   co_pessoa         d,
                   co_pessoa         c,
                   siw_solicitacao   g,
                   siw_tramite       f,
                   ac_acordo_log_arq j,
                   siw_arquivo       k
             where (b.destinatario       = d.sq_pessoa (+))
               and (b.cadastrador        = c.sq_pessoa)
               and (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (b.sq_acordo_log      = j.sq_acordo_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'FN' Then -- Se for o módulo financeiro
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_lancamento_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   decode(h.sq_lancamento_log,null,a.observacao,a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho) despacho,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                       a,
                   co_pessoa         c,
                   siw_tramite       e,
                   siw_solicitacao   g,
                   siw_tramite       f,
                   fn_lancamento_log h,
                   co_pessoa         i,
                   siw_solic_log_arq j,
                   siw_arquivo       k
             where (a.sq_pessoa          = c.sq_pessoa)
               and (a.sq_siw_tramite     = e.sq_siw_tramite)
               and (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (a.sq_siw_solic_log   = h.sq_siw_solic_log (+))
               and (h.destinatario       = i.sq_pessoa (+))
               and (a.sq_siw_solic_log   = j.sq_siw_solic_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and a.sq_siw_solicitacao = p_chave
            UNION
            select b.sq_lancamento_log, b.sq_siw_solic_log, 0, b.data_inclusao,  Nvl(b.despacho, b.observacao),
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   d.nome_resumido destinatario,
                   d.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase, f.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(b.data_inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from fn_lancamento_log                       b,
                   co_pessoa             d,
                   co_pessoa             c,
                   siw_solicitacao       g,
                   siw_tramite           f,
                   fn_lancamento_log_arq j,
                   siw_arquivo           k
             where (b.destinatario       = d.sq_pessoa (+))
               and (b.cadastrador        = c.sq_pessoa)
               and (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (b.sq_lancamento_log  = j.sq_lancamento_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_modulo = 'PD' Then -- Se for o módulo de viagens
      If p_restricao = 'LISTA' Then
         -- Recupera os encaminhamentos de uma demanda
         open p_result for
            select h.sq_demanda_log, a.sq_siw_solic_log, a.sq_siw_tramite,a.data,
                   decode(h.sq_demanda_log,null,a.observacao,a.observacao||chr(13)||chr(10)||'DESPACHO: '||chr(13)||chr(10)||h.despacho) despacho,
                   'TRAMITACAO' origem,
                   c.nome_resumido responsavel,
                   c.sq_pessoa,
                   i.nome_resumido destinatario,
                   i.sq_pessoa sq_pessoa_destinatario,
                   f.nome fase,
                   e.nome tramite,
                   k.sq_siw_arquivo, k.caminho, k.tipo, k.tamanho, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from siw_solic_log                     a,
                   co_pessoa         c,
                   siw_tramite       e,
                   siw_solicitacao   g,
                   siw_tramite       f,
                   gd_demanda_log    h,
                   co_pessoa       i,
                   siw_solic_log_arq j,
                   siw_arquivo     k
             where (a.sq_pessoa          = c.sq_pessoa)
               and (a.sq_siw_tramite     = e.sq_siw_tramite)
               and (a.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (a.sq_siw_solic_log   = h.sq_siw_solic_log (+))
               and (h.destinatario       = i.sq_pessoa (+))
               and (a.sq_siw_solic_log   = j.sq_siw_solic_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and a.sq_siw_solicitacao = p_chave
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
              from gd_demanda_log                     b,
                   co_pessoa          d,
                   co_pessoa          c,
                   siw_solicitacao    g,
                   siw_tramite        f,
                   gd_demanda_log_arq j,
                   siw_arquivo      k
             where (b.destinatario       = d.sq_pessoa (+))
               and (b.cadastrador        = c.sq_pessoa)
               and (b.sq_siw_solicitacao = g.sq_siw_solicitacao)
               and (g.sq_siw_tramite     = f.sq_siw_tramite)
               and (b.sq_demanda_log     = j.sq_demanda_log (+))
               and (j.sq_siw_arquivo     = k.sq_siw_arquivo (+))
               and b.sq_siw_solic_log   is null
               and b.sq_siw_solicitacao = p_chave;
      End If;
   End If;
End SP_GetSolicLog;
/
