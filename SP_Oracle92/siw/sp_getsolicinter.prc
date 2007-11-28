create or replace procedure SP_GetSolicInter
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   -- Recupera os interessados de uma solicitação
   -- tanto no formato novo quanto no formato antigo da tabela de interessados
   open p_result for 
      select null as sq_solicitacao_interessado, a.sq_siw_solicitacao, a.sq_pessoa, null as sq_tipo_interessado,
             a.tipo_visao, a.envia_email,
             b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind,
             c.email, c.ativo,
             d.sigla lotacao,
             '*** ALTERAR ***' nm_tipo_interessado, 0 or_tipo_interessado,
             1 ordena
        from gd_demanda_interes           a
             inner   join co_pessoa       b on (a.sq_pessoa  = b.sq_pessoa)
             inner   join sg_autenticacao c on (a.sq_pessoa  = c.sq_pessoa)
               inner join eo_unidade      d on (c.sq_unidade = d.sq_unidade)
       where a.sq_siw_solicitacao = p_chave
         and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_pessoa = p_chave_aux))
      UNION
      select null as sq_solicitacao_interessado, a.sq_siw_solicitacao, a.sq_pessoa, null as sq_tipo_interessado,
             a.tipo_visao, a.envia_email,
             b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind,
             c.email, c.ativo,
             d.sigla lotacao,
             '*** ALTERAR ***' nm_tipo_interessado, 0 or_tipo_interessado,
             1 ordena
        from pj_projeto_interes           a
             inner   join co_pessoa       b on (a.sq_pessoa  = b.sq_pessoa)
             inner   join sg_autenticacao c on (a.sq_pessoa  = c.sq_pessoa)
               inner join eo_unidade      d on (c.sq_unidade = d.sq_unidade)
       where a.sq_siw_solicitacao = p_chave
         and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_pessoa = p_chave_aux))
      UNION
      select a.sq_solicitacao_interessado, a.sq_siw_solicitacao, a.sq_pessoa, a.sq_tipo_interessado,
             a.tipo_visao, a.envia_email,
             b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind,
             c.email, c.ativo,
             d.sigla lotacao,
             e.nome nm_tipo_interessado, e.ordem or_tipo_interessado,
             0 ordena
        from siw_solicitacao_interessado       a
             inner   join co_pessoa            b on (a.sq_pessoa           = b.sq_pessoa)
             inner   join sg_autenticacao      c on (a.sq_pessoa           = c.sq_pessoa)
               inner join eo_unidade           d on (c.sq_unidade          = d.sq_unidade)
             inner   join siw_tipo_interessado e on (a.sq_tipo_interessado = e.sq_tipo_interessado)
       where a.sq_siw_solicitacao = p_chave
         and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_pessoa = p_chave_aux));
End SP_GetSolicInter;
/
