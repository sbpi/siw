create or replace procedure SP_GetPD_Alteracao
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_inicio    in date     default null,
    p_fim       in date     default null,
    p_numero    in varchar2 default null,
    p_pessoa    in varchar2 default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os registros de altera��o de viagem
      open p_result for
         select a.sq_pdalteracao as chave, a.sq_siw_solicitacao, a.sq_siw_arquivo, a.diaria_moeda, a.diaria_valor, 
                a.hospedagem_moeda, a.hospedagem_valor, a.bilhete_tarifa, a.bilhete_taxa, a.justificativa, 
                a.autorizacao_pessoa, a.autorizacao_cargo, a.autorizacao_data, a.inclusao, a.ultima_alteracao,
                b.nome as nm_arquivo, b.descricao as ds_arquivo, b.tamanho as tm_arquivo, b.tipo as tp_arquivo, 
                b.caminho as cm_arquivo, b.nome_original as nm_arquivo_original,
                c.sigla as sg_diaria_moeda, c.simbolo as sb_diaria_moeda,
                d.sigla as sg_hospedagem_moeda, d.simbolo as sb_hospedagem_moeda,
                e.nome as nm_autorizador, e.nome_resumido as nm_autorizador_resumido
           from pd_alteracao          a
                left join siw_arquivo b on (a.sq_siw_arquivo     = b.sq_siw_arquivo)
                left join co_moeda    c on (a.diaria_moeda       = c.sq_moeda)
                left join co_moeda    d on (a.hospedagem_moeda   = d.sq_moeda)
                left join co_pessoa   e on (a.autorizacao_pessoa = e.sq_pessoa)
          where a.sq_siw_solicitacao = p_chave
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_pdalteracao = p_chave_aux));   
   End If;         
End SP_GetPD_Alteracao;
/
